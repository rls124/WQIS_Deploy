<?= $this->Html->css('help.css') ?>
<div class="row">
	 <div class="col-lg-12 box" role="tablist" aria-multiselectable="true">
	 <h1> Water Quality Information System Help Page </h1>
		<p> This page is dedicated to help users navigate through the redesigned website. </p>
		<div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h2 class="heading mb-0">
                        <a class="collapsed" data-toggle="collapse"  href="#collapseOne">Map</a>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne">
				
                    <div class="card-block">
                        <p>The map has been switched from Google Maps API to a GIS based webmap. The core funcionality of the map should remain largely the same. Collection sites are highlighted on the map and when clicked displays the most recent data collected from that site. Selected sites also have a new option called "Zoom to", where once clicked on the map will zoom in on the site. The word <b>Map</b> can be clicked to collapse the entire section if it is unneeded.</p>
						<div class="card">
							<div class="card-header" role="tab" id="headingOneA">
								<h3 class="heading mb-0">
									<a class="collapsed" data-toggle="collapse"  href="#collapseOneA">Layers</a>
								</h3>
							</div>
							<div id="collapseOneA" class="collapse " role="tabpanel" aria-labelledby="headingOneA">
								<div class="card-block">
									<p>One of the main advantages of using a GIS based webmap is access to toggleable map layers. Layers are a way to display different geographical features or datasets on the map, for example, the "Watershed" layers highlights the different watersheds that data is collected from. These layers can be found at the bottom of the map as checkboxes. The current supported layers on the system are the Watersheds and the Drains layers, however there will be more added in the future. </p>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header" role="tab" id="headingOneB">
								<h3 class="heading mb-0">
									<a class="collapsed" data-toggle="collapse"  href="#collapseOneB">Basemap</a>
								</h3>
							</div>
							<div id="collapseOneB" class="collapse " role="tabpanel" aria-labelledby="headingOneB">
								<div class="card-block">
									<p>Basemaps are different backgrounds for the map that highlight different features. For example, the default 'Gray' basemap offers a minimal view that only highlights major roads and city names. Whereas the 'Streets' basemap displays all major and minor road ways in the area, but forgoes environmental details.  </p>
								</div>
							</div>
						</div>
                    </div>
                </div>
				
        </div>
	<div class="card">
        <div class="card-header" role="tab" id="headingTwo">
            <h2 class="heading mb-0">
                <a class="collapsed" data-toggle="collapse"  href="#collapseTwo">Search</a>
            </h2>
        </div>
        <div id="collapseTwo" class="collapse " role="tabpanel" aria-labelledby="headingTwo">		
            <div class="card-block">
                <p>To access the search functionality of the system click the blue box with 'Search' written in it. This will pull up a side menu that has many of the same search options the old version supported with a few others.</p>
				<div class="card">
					<div class="card-header" role="tab" id="headingTwoA">
						<h3 class="heading mb-0">
							<a class="collapsed" data-toggle="collapse"  href="#collapseTwoA">Sites</a>
						</h3>
					</div>
					<div id="collapseTwoA" class="collapse " role="tabpanel" aria-labelledby="headingTwoA">
						<div class="card-block">
							<p>The <b>Sites</b> section of the search is for selecting one or more collection sites to view data from. The user is able to select as many sites as they want, this will allow data between the two or more sites to be compared with each other on the timeline. There must be at least one site selected for the search to work properly.</p>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header" role="tab" id="headingTwoB">
						<h3 class="heading mb-0">
							<a class="collapsed" data-toggle="collapse"  href="#collapseTwoB">Where</a>
						</h3>
					</div>
					<div id="collapseTwoB" class="collapse " role="tabpanel" aria-labelledby="headingTwoB">
						<div class="card-block">
							<p>The <b>Where</b> section of the search is an <b>Optional</b> search parameter where a specific measure within a category can be searched by. This measure then can be searched over or under a user specified amount that can by typed into the bottom box. By filling out this section, the table section will only show results that are under or over the amount typed in. There will be no change on the timeline, as the result would be a broken graph.</p>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header" role="tab" id="headingTwoC">
						<h3 class="heading mb-0">
							<a class="collapsed" data-toggle="collapse"  href="#collapseTwoC">Display Fields</a>
						</h3>
					</div>
					<div id="collapseTwoC" class="collapse " role="tabpanel" aria-labelledby="headingTwoC">
						<div class="card-block">
							<p>The <b>Diplay Fields</b> Section is so the user can select or deselect which measures they are interested in. For example, if the Bacteria category is selected but the user only wishes to see E.Coli data on the timeline and graph, they can de-select the Cloiform field so that this data does not appear. Each selected measure will have its own graph in the timeline section.  </p>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header" role="tab" id="headingTwoD">
						<h3 class="heading mb-0">
							<a class="collapsed" data-toggle="collapse"  href="#collapseTwoD">Update and Reset</a>
						</h3>
					</div>
					<div id="collapseTwoD" class="collapse " role="tabpanel" aria-labelledby="headingTwoD">
						<div class="card-block">
							<p>The <b>Update and Reset</b> buttons are used to change the state of the Timeline, Graph, and Search areas. Modifications can be made in search section and the <b>Update</b> button is pressed to update the information in the Timeline and Table areas. The <b>reset</b> button can be presed to clear all the entries made in the search section, and to clear the displays in the Timeline and Table sections. </p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="card">
        <div class="card-header" role="tab" id="headingThree">
            <h2 class="heading mb-0">
                <a class="collapsed" data-toggle="collapse"  href="#collapseThree">Timeline</a>
            </h2>
        </div>
        <div id="collapseThree" class="collapse " role="tabpanel" aria-labelledby="headingThree">
            <div class="card-block">
                 <p>The <b>Timeline</b> section is for graphically organizing and displaying search results. The Timeline will show searched measures over a user defined period of time, these reults can be viewed in an in-line or grid format. The in-line format simply dedicated each graph to its own line while the grid layout will display all graphs in rows of two. A graph will appear for each selected field, and multiple lines will appear on the graph for each site selected. If multiple sites are selected, they will be highlighted unique colors so they will be able to stand out. The timeline graphs also have a option to display a red line that represents the admin defined becnhmark for any given measure. This can be toggled on and off with the <b>Show benchmark lines</b> checkbox near the layout buttons at the top. Like the Map and Graph section, the Timeline section can be folded away if the word <b>Timeline</b> is clicked.</p>
			</div>
        </div>
    </div>
	<div class="card">
        <div class="card-header" role="tab" id="headingFour">
            <h2 class="heading mb-0">
                <a class="collapsed" data-toggle="collapse"  href="#collapseFour">Export</a>
            </h2>
        </div>
        <div id="collapseFour" class="collapse " role="tabpanel" aria-labelledby="headingFour">
            <div class="card-block">
                 <p>The <b>Export</b> button at the bottom of the page is used for exporting the data listed in the table. The data is exported as a CSV (Comma Seperated Values) file. This is an Excel compatible file type that displays the table data is a similar format.</p>
			</div>
        </div>
    </div>			
	</div>
 </div>