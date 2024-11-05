<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apps</title>

    <!-- Default Theme CSS -->
    <link id="theme-css" rel="stylesheet" type="text/css"
        href="{{ asset('assets/gridphp/themes/redmond/jquery-ui.custom.css') }}">

    <!-- jqGrid CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/gridphp/jqgrid/css/ui.jqgrid.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/gridphp/jqgrid/css/ui.jqgrid.bs.css') }}">

    <!-- jQuery and jQuery UI JS -->
    <script src="{{ asset('assets/gridphp/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/gridphp/themes/jquery-ui.custom.min.js') }}"></script>

    <!-- jqGrid JS -->
    <script src="{{ asset('assets/gridphp/jqgrid/js/i18n/grid.locale-en.js') }}"></script>
    <script src="{{ asset('assets/gridphp/jqgrid/js/jquery.jqGrid.min.js') }}"></script>

    <script type="text/javascript">
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        // Function to change themes dynamically
        function changeTheme(theme) {
            var themeCssPath = "{{ asset('assets/gridphp/themes/') }}/" + theme + "/jquery-ui.custom.css";
            document.getElementById('theme-css').setAttribute('href', themeCssPath);
        }
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

    <!-- Main Grid Output -->
    <div>{!! $appsubgrid !!}</div>
    <!-- <h1>Welcome</h1> -->

    <br>
    <!-- <button onclick="$('#list1').gridState().remove('gridState-list-parentgrid'); location.reload();">Forget Settings</button> -->

    <script type="text/javascript">
        function expand_all() {
            var rowIds = jQuery("#list1").getDataIDs();
            jQuery.each(rowIds, function(index, rowId) {
                jQuery("#list1").expandSubGridRow(rowId);
            });
        }

        setTimeout(() => {
            jQuery(document).ready(function() {
                jQuery('#list1').jqGrid('navButtonAdd', '#list1_pager', {
                    'caption': 'Toggle Expand',
                    'buttonicon': 'ui-icon-plus',
                    'onClickButton': function() {

                        var rowIds = jQuery("#list1").getDataIDs();

                        if (!jQuery(document).data('expandall')) {
                            jQuery.each(rowIds, function(index, rowId) {
                                jQuery("#list1").expandSubGridRow(rowId);
                            });
                            jQuery(document).data('expandall', 1);
                        } else {
                            jQuery.each(rowIds, function(index, rowId) {
                                jQuery("#list1").collapseSubGridRow(rowId);
                            });
                            jQuery(document).data('expandall', 0);
                        }

                    },
                    'position': 'last'
                });
            });
        }, 200)
    </script>
</body>

</html>
