(function () {
    'use strict';

    //===================================
    //   Safe arithmetic evaluator (no eval)
    //   Grammar:
    //     expression = term   (('+' | '-') term)*
    //     term       = unary  (('*' | '/') unary)*
    //     unary      = ('+' | '-') unary | power
    //     power      = primary ('**' unary)?        (right-associative)
    //     primary    = number | '(' expression ')'
    function evaluate(expression) {
        const NUMBER = /^(\d+\.?\d*|\.\d+)$/;
        const tokens = expression.match(/\d+\.?\d*|\.\d+|\*\*|[-+*/()]|\S/g) || [];
        let pos = 0;

        const peek = () => tokens[pos];
        const next = () => tokens[pos++];

        function parseExpression() {
            let value = parseTerm();
            while (peek() === '+' || peek() === '-') {
                value = next() === '+' ? value + parseTerm() : value - parseTerm();
            }
            return value;
        }

        function parseTerm() {
            let value = parseUnary();
            while (peek() === '*' || peek() === '/') {
                value = next() === '*' ? value * parseUnary() : value / parseUnary();
            }
            return value;
        }

        function parseUnary() {
            if (peek() === '-') { next(); return -parseUnary(); }
            if (peek() === '+') { next(); return parseUnary(); }
            return parsePower();
        }

        function parsePower() {
            const base = parsePrimary();
            if (peek() === '**') { next(); return base ** parseUnary(); }
            return base;
        }

        function parsePrimary() {
            const token = next();
            if (token === '(') {
                const value = parseExpression();
                if (next() !== ')') throw new SyntaxError('Missing closing parenthesis');
                return value;
            }
            if (token !== undefined && NUMBER.test(token)) return parseFloat(token);
            throw new SyntaxError('Unexpected input');
        }

        const value = parseExpression();
        if (pos < tokens.length) throw new SyntaxError('Unexpected input');
        return value;
    }

    // Allow unit testing with Node: `require('./calculator.js').evaluate('1+2')`
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = { evaluate };
        return;
    }

    //===================================
    //   Calculator UI (buttons + keyboard)
    document.addEventListener('DOMContentLoaded', function () {
        const display = document.getElementById('calc-display');
        if (!display) {
            return;
        }

        function press(key) {
            // Start fresh after an error message
            if (display.value === 'Error') {
                display.value = '';
            }

            if (key === '=') {
                if (display.value.trim() !== '') {
                    try {
                        display.value = String(evaluate(display.value));
                    } catch (e) {
                        display.value = 'Error';
                    }
                }
            } else if (key === 'C') {
                display.value = '';
            } else if (key === 'back') {
                display.value = display.value.slice(0, -1);
            } else {
                display.value += key;
            }
        }

        document.querySelectorAll('[data-key]').forEach(button => {
            button.addEventListener('click', () => press(button.dataset.key));
        });

        // Keyboard: digits/operators type, Enter or = evaluates, Backspace deletes, Escape clears
        document.addEventListener('keydown', event => {
            if (event.ctrlKey || event.metaKey || event.altKey) {
                return;
            }
            let key = null;
            if (/^[0-9+\-*/().]$/.test(event.key)) key = event.key;
            else if (event.key === 'Enter' || event.key === '=') key = '=';
            else if (event.key === 'Backspace') key = 'back';
            else if (event.key === 'Escape' || event.key === 'Delete') key = 'C';

            if (key !== null) {
                event.preventDefault();
                press(key);
            }
        });
    });
})();
