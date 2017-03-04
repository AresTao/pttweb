<?php
	if (SessionManager::getInstance()->isAdmin()) {
?>
		<script type="text/javascript">/*<![CDATA[*/
			function jq_loadPage(page){
				$.get('./?ajax=getPage&page='+page, {},
						function(data){
							$('#content').html(data);
						}
					);
			}
			/*]]>*/
		</script>

		
		 <div class="clear"></div>
<div id="dcFooter">
 <div id="footer">
  <div class="line"></div>
  <ul>
   版权所有 © 2013-2016 群辉科技有限公司，并保留所有权利。<?php echo '<div class="">Server Version: ' . ServerInterface::getInstance()->getVersion() . '</div>';?>
  <?php echo '<div class="">Script execution time: '.sprintf('%6.3f', PHPStats::scriptExecTimeGet()).'ms | memory peak: '.(memory_get_peak_usage()/1000).' kByte</div>';?>
  </ul>
 </div>
</div><!-- dcFooter 结束 -->
<div class="clear"></div> </div>

<?php
		//if (!isset($_SESSION['mumpiVersionCheckLast'])) {
		//	$_SESSION['mumpiVersionCheckLast'] = 0;
		//}
		// only check every 60 minutes
		//TODO make this cache the result and display the cached one when not querying
		//if ($_SESSION['mumpiVersionCheckLast']+3600 < time()) {
			include_once(MUMPHPI_MAINDIR.'/version.php');
			if (isset($mumpiVersion)) {
				$_SESSION['mumpiVersionCheckLast'] = time();
?>
				<script type="text/javascript">/*<![CDATA[*/
						function mumpi_jsonp(data)
						{
							jQuery('.updatecheck_result').html(data);
						}
					/*]]>*/
				</script>
				
<?php
			}
		//}
	}
?>
