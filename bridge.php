<!DOCTYPE html>
<html>
	<head>
		<title>NaviCom</title>
		<link type="text/css" rel="stylesheet" href="./bridge.css"/>
		<script type="text/javascript" src="jscolor/jscolor.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="./bridge.js"></script>
	</head>
	<body>
		<noscript>
		This page relies on javascript to perform its functions. You must allow javascript in your browser to be able to use i.
		</noscript>

		<h1>NaviCom</h1>

		<p>
			Welcome to NaviCom web service, a link between cBioPortal database and NaviCell web service.<br/>
			Select a study from which you want to fetch data, the map on which you want it to be displayed and the type of display you want to see.<br/>
			NaviCom uses the display function defined in the <a href="https://github.com/MathurinD/navicom">navicom</a> python package.<br/>
			Note that downloading data from cBioPortal and exporting them to NaviCell can take some time.<br/>
		</p>

		<!--
		<h2>Possibility 1</h2>

		<form id="nc_config_bis">
			<table>
			<tr>
				<td><label for="study_selection">Study:</label></td>
				<td>
				<select >
					<option value="empty"></option>
				</select>
				</td>
				<td>
					or
				</td>
				<td>
					<input type="file">
				</td>
			</tr>
			<tr>
				<td><label for="map_selection">Map:</label></td>
				<td>
				<select >
					<option value="global_map">ACSN global map</option>
				</select>
				</td>
				<td>
					or
				</td>
				<td>
					<input type="file">
				</td>
			</tr>
			<tr>
				<td><label for="display_selection">Display mode:</label></td>
				<td colspan=2>
				<select>
					<option value="completeDisplay">Complete display</option>
					<option value="displayOmics">Omics display</option>
					<option value="completeExport">Complete export</option>
				</select>
				</td>
			</tr>
			</table>

				<button id="nc_perform" onclick="exec_navicom()">Perform display</button><br/>

				<label for="display_config">Display configuration</label>
		</form>

		<h2>Possibility 2</h2>
		-->

		<?php
		echo("Hello body");
		?>
		<form id="nc_config" target="_blank">
			<table>
				<fieldset>
				<legend for="study_selection">Study:</legend>
				<select id="study_selection">
					<option value="empty" selected="selected">&nbsp;</option>
<?php
$studies = array();
exec("Rscript listStudies.R", $studies, $return);

echo("Hello $return");
if ($return != 0) {
	echo("<p>An error occured while listing the studies</p>");
} else {
	echo('<option value="non">Nothing</option>');
}
?>
				</select>
				<!--or <input type="file" id="study_file">-->
				</fieldset>
				<button id="data_download" onclick="download_data()" type="button">Download cBioPortal data</button>

				<fieldset>
				<legend for="map_selection">Map:</legend>
				<select id="map_selection">
					<option value="acsn" title="The global map of ACSN">ACSN global map</option>
					<option value="apoptosis" title="Apoptosis and mitochondria metabolism map">Apoptosis map</option>
					<option value="survival" title="Cell survival map">Cell survival map</option>
					<option value="emtcellmobility" title="Epithelial-to-mesenchymal transition and cell mobitility map">EMT and cell mobility map</option>
					<option value="cellcycle" title="Cell cycle map">Cell cycle map</option>
					<option value="dnarepair" title="DNA repair map">DNA repair map</option>
				</select>
				or <input type="text" title="URL of a NaviCell map" id="map_url">
				</fieldset>
				<!-- TODO input fields to specify local data or another map -->

				<fieldset>
				<legend for="display_selection">Display mode:</legend>
				<select id="display_selection">
					<option value="completeDisplay" title="A dense display with as many data as possible displayed on the map" selected>Complete display</option>
					<!--<option value="displayOmics" title="Display on omics data available in the dataset">Omics display</option>-->
					<!--<option value="completeExport" title="Export all data available for the dataset to  NaviCell">Complete export</option>-->
					<option value="displayMethylome" title="Display methylation data on top of ">Display methylation data</option>
					<option value="displayMutations">Display mutations data</option>
				</select>
				</fieldset>

				<!--<fieldset id="samples_selection">-->
					<!--<legend></legend>-->
					<!--Choose groups to display-->
				<!--</fieldset>-->

				<fieldset>
					<legend>Display configuration</legend>
					<!--TODO write a selection-->
					Color for lowest values: <input class="color" id="low_color" value="00FF00"/><br/>
					Color for highest values: <input class="color" id="high_color" value="FF0000"/><br/>
					Color for zero (if present): <input class="color" id="zero_color" value"FFFFFF"><br/>
				</fieldset>

				<button id="nc_perform" onclick="exec_navicom()" type="button">Perform display</button><br/>
		</form>

		<p>
			<img src="./ajax-loader.gif" id="loading_spinner"/>
		</p>
		<section id="logs">
			<!--<?php echo $_GET["log_msg"] ?>-->
		</section>
	</body>
</html>