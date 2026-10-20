(function () {
    'use strict';

    const SETS = {
        lower:   'abcdefghijklmnopqrstuvwxyz',
        upper:   'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        digits:  '0123456789',
        symbols: '!@#$%^&*()-_=+[]{};:,.?/',
    };
    const AMBIGUOUS = /[Il1Oo0]/g;

    //===================================
    //   Uniform random integer in [0, max) from the browser's CSPRNG.
    //   Rejection sampling avoids the modulo bias of `random % max`.
    function randomInt(max) {
        const limit = Math.floor(0x100000000 / max) * max;
        const buffer = new Uint32Array(1);
        do {
            crypto.getRandomValues(buffer);
        } while (buffer[0] >= limit);
        return buffer[0] % max;
    }

    // Character sets selected in the options (optionally without look-alike characters)
    function selectedSets(options) {
        return Object.keys(SETS)
            .filter(name => options[name])
            .map(name => options.avoidAmbiguous ? SETS[name].replace(AMBIGUOUS, '') : SETS[name]);
    }

    //===================================
    //   Generate a password containing at least one character of every selected set
    function generatePassword(options) {
        const sets = selectedSets(options);
        if (sets.length === 0) {
            throw new Error('Select at least one character set.');
        }
        const length = Math.max(options.length, sets.length);
        const pool   = sets.join('');

        // One character from each selected set, the rest from the whole pool...
        const chars = sets.map(set => set[randomInt(set.length)]);
        while (chars.length < length) {
            chars.push(pool[randomInt(pool.length)]);
        }

        // ...then a Fisher-Yates shuffle so the guaranteed characters aren't always first
        for (let i = chars.length - 1; i > 0; i--) {
            const j = randomInt(i + 1);
            [chars[i], chars[j]] = [chars[j], chars[i]];
        }
        return chars.join('');
    }

    // Approximate strength in bits: length * log2(pool size)
    function entropyBits(options) {
        const poolSize = selectedSets(options).join('').length;
        return poolSize > 0 ? Math.round(options.length * Math.log2(poolSize)) : 0;
    }

    // [label, Bootstrap color, progress %]
    function strength(bits) {
        if (bits < 50)  return ['Weak', 'danger', 25];
        if (bits < 70)  return ['Fair', 'warning', 50];
        if (bits < 100) return ['Strong', 'info', 75];
        return ['Very strong', 'success', 100];
    }

    // Allow unit testing with Node: `require('./password-generator.js')`
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = { generatePassword, entropyBits, strength, randomInt, SETS };
        return;
    }

    //===================================
    //   UI
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('password-options');
        if (!form) {
            return;
        }
        const output      = document.getElementById('password-output');
        const lengthInput = document.getElementById('pw-length');
        const lengthValue = document.getElementById('pw-length-value');
        const meter       = document.getElementById('pw-strength-bar');
        const meterLabel  = document.getElementById('pw-strength-label');
        const setBoxes    = ['lower', 'upper', 'digits', 'symbols'].map(name => document.getElementById('pw-' + name));

        function readOptions() {
            return {
                length:         parseInt(lengthInput.value, 10),
                lower:          setBoxes[0].checked,
                upper:          setBoxes[1].checked,
                digits:         setBoxes[2].checked,
                symbols:        setBoxes[3].checked,
                avoidAmbiguous: document.getElementById('pw-ambiguous').checked,
            };
        }

        function refresh() {
            const options = readOptions();
            lengthValue.textContent = options.length;
            output.value = generatePassword(options);

            const bits = entropyBits(options);
            const [label, color, percent] = strength(bits);
            meter.className = 'progress-bar bg-' + color;
            meter.style.width = percent + '%';
            meter.parentElement.setAttribute('aria-valuenow', String(percent));
            meterLabel.textContent = `${label} · ~${bits} bits of entropy`;
        }

        form.addEventListener('submit', event => event.preventDefault());
        form.addEventListener('input', event => {
            // Keep at least one character set selected
            if (setBoxes.includes(event.target) && !setBoxes.some(box => box.checked)) {
                event.target.checked = true;
            }
            refresh();
        });
        document.getElementById('pw-regenerate').addEventListener('click', refresh);

        refresh();
    });
})();
