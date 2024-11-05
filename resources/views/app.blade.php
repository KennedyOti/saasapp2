<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apps</title>

    <!-- CSS and JS for jqGrid and themes -->
    <link id="theme-css" rel="stylesheet" href="{{ asset('assets/gridphp/themes/redmond/jquery-ui.custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/gridphp/jqgrid/css/ui.jqgrid.css') }}">
    <script src="{{ asset('assets/gridphp/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/gridphp/themes/jquery-ui.custom.min.js') }}"></script>
    <script src="{{ asset('assets/gridphp/jqgrid/js/i18n/grid.locale-en.js') }}"></script>
    <script src="{{ asset('assets/gridphp/jqgrid/js/jquery.jqGrid.min.js') }}"></script>

    <script>
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        // Function to change themes
        function changeTheme(theme) {
            var themeCssPath = "{{ asset('assets/gridphp/themes/') }}/" + theme + "/jquery-ui.custom.css";
            document.getElementById('theme-css').setAttribute('href', themeCssPath);
        }

        $(document).ready(function() {
            // Toggle grouping based on selected column
            $('#toggleGroup').on('click', function() {
                let selectedColumn = $('.chngroup').val();
                if (selectedColumn && selectedColumn !== "clear") {
                    $("#list1").jqGrid('groupingGroupBy', selectedColumn);
                } else {
                    $("#list1").jqGrid('groupingRemove', true);
                }
            });

            // Open specific groups
            $('.opengroup').on('change', function() {
                let groupIndex = $(this).val();
                if (groupIndex) {
                    $("#list1").jqGrid('groupingToggle', groupIndex);
                }
            });
        });
    </script>
</head>

<body>
    <!-- Theme Selector -->
    <div>
        <label for="theme-selector">Select Theme:</label>
        <select id="theme-selector" onchange="changeTheme(this.value)">
            <option value="redmond">Redmond</option>
            <option value="smoothness">Smoothness</option>
            <option value="south-street">South Street</option>
            <option value="start">Start</option>
            <option value="sunny">Sunny</option>
            <option value="swanky-purse">Swanky Purse</option>
            <option value="trontastic">Trontastic</option>
            <option value="ui-darkness">UI Darkness</option>
            <option value="ui-lightness">UI Lightness</option>
            <option value="vader">Vader</option>
        </select>
    </div>

    <!-- Dynamic Grouping Controls -->
    <div style="margin:10px">
        <label>Dynamic Group By:</label>
        <select class="chngroup">
            <option value="">-Select-</option>
            @foreach ($colModel as $column)
                <option value="{{ $column['name'] }}">{{ $column['title'] }}</option>
            @endforeach
            <option value="clear">Clear</option>
        </select>
        <button id="toggleGroup">Toggle Grouping</button>

        <select class="opengroup">
            <option value="">-Open Group-</option>
            <option value="0">First</option>
            <option value="1">Second</option>
            <option value="2">Third</option>
        </select>
    </div>

    <!-- Grid Output -->
    <div>{!! $grid !!}</div>
</body>

</html>
