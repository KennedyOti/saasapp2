<x-layout>
    <div>{!! $clientGrid !!}</div>
    <script type="text/javascript">
	
	function expand_all()
	{
		var rowIds = jQuery("#list1").getDataIDs();
		jQuery.each(rowIds, function (index, rowId) { jQuery("#list1").expandSubGridRow(rowId); });
	}
	
	setTimeout(()=>{
		jQuery(document).ready(function(){
			jQuery('#list1').jqGrid('navButtonAdd', '#list1_pager',
			{
				'caption'      : 'Toggle Expand',
				'buttonicon'   : 'ui-icon-plus',
				'onClickButton': function()
				{

					var rowIds = jQuery("#list1").getDataIDs();

					if ( ! jQuery(document).data('expandall') )
					{
						jQuery.each(rowIds, function (index, rowId) { jQuery("#list1").expandSubGridRow(rowId); });
						jQuery(document).data('expandall',1);
					}
					else
					{
						jQuery.each(rowIds, function (index, rowId) { jQuery("#list1").collapseSubGridRow(rowId); });
						jQuery(document).data('expandall',0);
					}

				},
				'position': 'last'
			});
		});
	},200)
	</script>
</x-layout>