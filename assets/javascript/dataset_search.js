var format = function (dataset) {
    console.log("dataset", dataset);
    if (dataset.loading) return dataset.text;

    return "<div class='dataset-item'>" + dataset.text + "</div>";
};

var formatSelection = function (dataset) {
    console.log("formatSelection", dataset);
    return dataset.text || dataset.text;
};

var escapeFn = function(m) {
    return m;
}

function dataFn(params) {
    console.log("data", params);
    return { q: params.term };
};

function noMatchFn() {
    return "Keine Treffer gefunden!";
}

function langaugeFn() {
    // German
    return {
        inputTooLong: function (args) {
            var overChars = args.input.length - args.maximum;

            return 'Bitte ' + overChars + ' Zeichen weniger eingeben';
        },
        inputTooShort: function (args) {
            var remainingChars = args.minimum - args.input.length;

            return 'Bitte ' + remainingChars + ' Zeichen mehr eingeben';
        },
        loadingMore: function () {
            return 'Lade mehr Ergebnisse…';
        },
        maximumSelected: function (args) {
            var message = 'Sie können nur ' + args.maximum + ' Eintr';

            if (args.maximum === 1) {
                message += 'ag';
            } else {
                message += 'äge';
            }

            message += ' auswählen';

            return message;
        },
        noResults: function () {
            return 'Keine Übereinstimmungen gefunden';
        },
        searching: function () {
            return 'Suche…';
        }
    };
}

var resultFn = function (result, page) {
    console.log("result", result);
    return {
        results: jQuery.map(result.result.results, function(obj) {
            obj.id = obj.name;
            obj.text = obj.title.de;
            obj.title = obj.title.de;
            return obj;
        })
    };
};
