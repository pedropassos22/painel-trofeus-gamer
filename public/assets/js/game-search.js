(function () {
    'use strict';

    var input = document.getElementById('q');

    if (!input) {
        return;
    }

    var url = input.getAttribute('data-suggest-url');

    if (!url) {
        return;
    }

    var MIN_CHARS = 3;
    var WAIT_MS = 500;

    var box = document.createElement('div');
    box.className = 'suggest-box';
    box.hidden = true;
    input.parentNode.appendChild(box);

    var cache = new Map();
    var timer = null;
    var controller = null;

    function hideBox() {
        box.hidden = true;
        box.innerHTML = '';
    }

    function showMessage(text) {
        box.innerHTML = '';

        var message = document.createElement('div');
        message.className = 'suggest-message';
        message.textContent = text;
        box.appendChild(message);

        box.hidden = false;
    }

    function showItems(items) {
        box.innerHTML = '';

        items.forEach(function (item) {
            var row = document.createElement('div');
            row.className = 'suggest-item';

            var name = document.createElement('span');
            name.textContent = item.nome;
            row.appendChild(name);

            var info = document.createElement('small');
            info.textContent = item.lancamento_status === 'anunciado'
                ? 'Anunciado'
                : (item.ano_lancamento || '');
            row.appendChild(info);

            // mousedown acontece antes de o campo perder o foco
            row.addEventListener('mousedown', function (event) {
                event.preventDefault();
                input.value = item.nome;
                hideBox();
            });

            box.appendChild(row);
        });

        box.hidden = false;
    }

    function render(data) {
        if (data.tooMany) {
            showMessage('Muitos resultados. Continue digitando para ver as sugestões.');
            return;
        }

        if (!data.items || !data.items.length) {
            showMessage('Nenhum jogo encontrado.');
            return;
        }

        showItems(data.items);
    }

    function isCurrent(key) {
        return input.value.trim().toLowerCase() === key
            && document.activeElement === input;
    }

    function fetchSuggestions(term) {
        var key = term.toLowerCase();

        if (controller) {
            controller.abort();
        }

        controller = new AbortController();

        showMessage('Buscando...');

        fetch(url + '?q=' + encodeURIComponent(term), {
            signal: controller.signal,
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('erro');
                }

                return response.json();
            })
            .then(function (data) {
                cache.set(key, data);

                if (isCurrent(key)) {
                    render(data);
                }
            })
            .catch(function (error) {
                if (error.name === 'AbortError') {
                    return;
                }

                if (isCurrent(key)) {
                    showMessage('Não foi possível buscar agora.');
                }
            });
    }

    input.addEventListener('input', function () {
        var term = input.value.trim();

        clearTimeout(timer);

        if (controller) {
            controller.abort();
        }

        if (term.length < MIN_CHARS) {
            hideBox();
            return;
        }

        var cached = cache.get(term.toLowerCase());

        if (cached) {
            render(cached);
            return;
        }

        timer = setTimeout(function () {
            fetchSuggestions(term);
        }, WAIT_MS);
    });

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            hideBox();
        }
    });

    input.addEventListener('blur', hideBox);
})();