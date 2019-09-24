function changeMeasures() {
    dropMeasures();
    var chosenMeasure = document.getElementById('categorySelect');
    var bacteriaData = {'select': ['Select a measure'],
        'ecoli': ['E. Coli (CFU/100 mil)']};
    var nutrientData = {'select': ['Select a measure'],
        'nitrateNitrite': ['Nitrate/Nitrite (mg/L)'],
        'phosphorus': ['Total Phosphorus (mg/L)'],
        'drp': ['Dissolved Reactive Phosphorus (mg/L)']};
    var pesticideData = {'select': ['Select a measure'],
        'alachlor': ['Alachlor (µg/L)'],
        'atrazine': ['Atrazine (µg/L)'],
        'metolachlor': ['Metolachlor (µg/L)']};
    var physProp = {'select': ['Select a measure'],
        'conductivity': ['Conductivity (mS/cm)'],
        'do': ['Dissolved Oxygen (mg/L'],
        'ph': ['pH'],
        'water_temp': ['Water Temperature (°C)'],
        'tds': ['Total Dissolved Solids (g/L)'],
        'turbidity': ['Turbidity (NTU)']};

    switch (chosenMeasure.value) {
        case 'bacteria':
            populateMeasurementSelect(bacteriaData);
            break;
        case 'nutrient':
            populateMeasurementSelect(nutrientData);
            break;
        case 'pesticide':
            populateMeasurementSelect(pesticideData);
            break;
        case 'wqm':
            populateMeasurementSelect(physProp);
            break;
    }
}

function dropMeasures() {
    var measureSelect = document.getElementById('measurementSelect');
    while (measureSelect.options.length > 0) {
        measureSelect.remove(0);
    }
}

function populateMeasurementSelect(categoryData) {
    for (var i in categoryData) {
        var option = document.createElement('option');
        option.value = i;
        option.text = categoryData[i];
        document.getElementById('measurementSelect').appendChild(option);
    }
}

$(document).ready(function () {
    document.getElementById('categorySelect').addEventListener("change", changeMeasures);
    $(".date-picker").datepicker({
        trigger: "focus",
        format: 'mm/dd/yyyy',
        todayHighlight: true,
        todayBtn: "linked"
    });

    $("#startdate").datepicker().on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endDate').datepicker('setStartDate', minDate);
    });
    $("#enddate").datepicker().on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#startDate').datepicker('setEndDate', maxDate);
    });
});
