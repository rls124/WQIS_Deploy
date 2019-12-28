$(document).ready(function() {
	var easter_egg = new Konami(function() { loadTetris() });
	
	function loadTetris() {
		var map = document.getElementById("map");
		map.style.display = "none";
		
		var easterEggDiv = document.getElementById("easteregg");
		easterEggDiv.style.display = "block";
		
		run();
	}
	
	function hide(id) {
		document.getElementById(id).style.visibility = 'hidden';
	}
	
	function show(id) {
		document.getElementById(id).style.visibility = null;
	}
	
	function html(id, html) {
		document.getElementById(id).innerHTML = html;
	}

	function timestamp() {
		return new Date().getTime();
	}
	
	function random(min, max) {
		return (min + (Math.random() * (max - min)));
	}
	
	function randomChoice(choices) {
		return choices[Math.round(random(0, choices.length-1))];
	}

	if (!window.requestAnimationFrame) {
		window.requestAnimationFrame = window.webkitRequestAnimationFrame || 
			window.mozRequestAnimationFrame    || 
			window.oRequestAnimationFrame      || 
			window.msRequestAnimationFrame     || 
			function(callback, element) {
				window.setTimeout(callback, 1000 / 60);
			}
	}

	//game constants
	var KEY     = { ESC: 27, SPACE: 32, LEFT: 37, UP: 38, RIGHT: 39, DOWN: 40 },
		DIR     = { UP: 0, RIGHT: 1, DOWN: 2, LEFT: 3, MIN: 0, MAX: 3 },
		canvas  = document.getElementById('canvas'),
		ctx     = canvas.getContext('2d'),
		ucanvas = document.getElementById('upcoming'),
		uctx    = ucanvas.getContext('2d'),
		speed   = { start: 0.6, decrement: 0.005, min: 0.1 }, // how long before piece drops by 1 row (seconds)
		nx      = 10, //width of tetris court (in blocks)
		ny      = 20, //height of tetris court (in blocks)
		nu      = 5;  //width/height of upcoming preview (in blocks)

	//game variables (initialized during reset)
	var dx, dy,        //pixel size of a single tetris block
		blocks,        //2 dimensional array (nx*ny) representing tetris court - either empty block or occupied by a 'piece'
		actions,       //queue of user actions (inputs)
		playing,       //true|false - game is in progress
		dt,            //time since starting this game
		current,       //the current piece
		next,          //the next piece
		score,         //the current score
		vscore,        //the currently displayed score (it catches up to score in small chunks - like a spinning slot machine)
		rows,          //number of completed rows in the current game
		level,         //current level number
		step;          //how long before current piece drops by 1 row

	/*
	tetris pieces

	blocks: each element represents a rotation of the piece (0, 90, 180, 270)
			each element is a 16 bit integer where the 16 bits represent
			a 4x4 set of blocks, e.g. j.blocks[0] = 0x44C0
    
				0100 = 0x4 << 3 = 0x4000
				0100 = 0x4 << 2 = 0x0400
				1100 = 0xC << 1 = 0x00C0
				0000 = 0x0 << 0 = 0x0000
								  ------
								  0x44C0  
	*/

	var i = { size: 4, blocks: [0x0F00, 0x2222, 0x00F0, 0x4444], color: 'cyan'   };
	var j = { size: 3, blocks: [0x44C0, 0x8E00, 0x6440, 0x0E20], color: 'blue'   };
	var l = { size: 3, blocks: [0x4460, 0x0E80, 0xC440, 0x2E00], color: 'orange' };
	var o = { size: 2, blocks: [0xCC00, 0xCC00, 0xCC00, 0xCC00], color: 'yellow' };
	var s = { size: 3, blocks: [0x06C0, 0x8C40, 0x6C00, 0x4620], color: 'green'  };
	var t = { size: 3, blocks: [0x0E40, 0x4C40, 0x4E00, 0x4640], color: 'purple' };
	var z = { size: 3, blocks: [0x0C60, 0x4C80, 0xC600, 0x2640], color: 'red'    };

	//do the bit manipulation and iterate through each occupied block (x,y) for a given piece
	function eachblock(type, x, y, dir, fn) {
		var bit, result, row = 0, col = 0, blocks = type.blocks[dir];
		for (bit = 0x8000 ; bit > 0 ; bit = bit >> 1) {
			if (blocks & bit) {
				fn(x + col, y + row);
			}
			if (++col === 4) {
				col = 0;
				++row;
			}
		}
	};

	//check if a piece can fit into a position in the grid
	function occupied(type, x, y, dir) {
		var result = false
		eachblock(type, x, y, dir, function(x, y) {
			if ((x < 0) || (x >= nx) || (y < 0) || (y >= ny) || getBlock(x,y))
			result = true;
		});
		return result;
	};

	function unoccupied(type, x, y, dir) {
		return !occupied(type, x, y, dir);
	};

	//start with 4 instances of each piece and pick randomly until the 'bag is empty'
	var pieces = [];
	function randomPiece() {
		if (pieces.length == 0) {
			pieces = [i,i,i,i,j,j,j,j,l,l,l,l,o,o,o,o,s,s,s,s,t,t,t,t,z,z,z,z];
		}
		
		var type = pieces.splice(random(0, pieces.length-1), 1)[0];
		return { type: type, dir: DIR.UP, x: Math.round(random(0, nx - type.size)), y: 0 };
	};

	//game loop
	function run() {
		addEvents(); // attach keydown and resize events

	var last = now = timestamp();
		function frame() {
			now = timestamp();
			update(Math.min(1, (now - last) / 1000.0)); // using requestAnimationFrame have to be able to handle large deltas caused when it hibernates in a background or non-visible tab
			draw();
			last = now;
			requestAnimationFrame(frame, canvas);
		}

		resize(); // setup all our sizing information
		reset();  // reset the per-game variables
		frame();  // start the first frame
	};

	function addEvents() {
		document.addEventListener('keydown', keydown, false);
		window.addEventListener('resize', resize, false);
	};

	function resize(event) {
		//set canvas logical size equal to its physical size
		canvas.width   = canvas.clientWidth;
		canvas.height  = canvas.clientHeight;
		ucanvas.width  = ucanvas.clientWidth;
		ucanvas.height = ucanvas.clientHeight;

		//pixel size of a single tetris block
		dx = canvas.width  / nx;
		dy = canvas.height / ny;
		invalidate();
		invalidateNext();
	};

	function keydown(ev) {
		var handled = false;
		if (playing) {
			switch(ev.keyCode) {
				case KEY.LEFT:   actions.push(DIR.LEFT);  handled = true; break;
				case KEY.RIGHT:  actions.push(DIR.RIGHT); handled = true; break;
				case KEY.UP:     actions.push(DIR.UP);    handled = true; break;
				case KEY.DOWN:   actions.push(DIR.DOWN);  handled = true; break;
				//case KEY.SPACE:  actions.push(DIR.SPACE); handled = true; break;
				case KEY.ESC:    lose();                  handled = true; break;
			}
		}
		else if (ev.keyCode == KEY.SPACE) {
			play();
			handled = true;
		}
		
		if (handled) {
			ev.preventDefault(); // prevent arrow keys from scrolling the page (supported in IE9+ and all other browsers)
		}
	};

	//game logic
	function play() { hide('start'); reset();          playing = true;  };
	function lose() { show('start'); setVisualScore(); playing = false; };

	function setVisualScore(n)      { vscore = n || score; invalidateScore(); };
	function setScore(n)            { score = n; setVisualScore(n);  };
	function addScore(n)            { score = score + n;   };
	function clearScore()           { setScore(0); };
	function clearRows()            { setRows(0); };
	function setRows(n)             { rows = n; step = Math.max(speed.min, speed.start - (speed.decrement*rows)); invalidateRows(); };
	function addRows(n)             { setRows(rows + n); };
	function getBlock(x,y)          { return (blocks && blocks[x] ? blocks[x][y] : null); };
	function setBlock(x,y,type)     { blocks[x] = blocks[x] || []; blocks[x][y] = type; invalidate(); };
	function clearBlocks()          { blocks = []; invalidate(); }
	function clearActions()         { actions = []; };
	function setCurrentPiece(piece) { current = piece || randomPiece(); invalidate();     };
	function setNextPiece(piece)    { next    = piece || randomPiece(); invalidateNext(); };

	function reset() {
		dt = 0;
		clearActions();
		clearBlocks();
		clearRows();
		clearScore();
		setCurrentPiece(next);
		setNextPiece();
	};

	function update(idt) {
		if (playing) {
			if (vscore < score) {
				setVisualScore(vscore + 1);
			}
			
			handle(actions.shift());
			dt = dt + idt;
			if (dt > step) {
				dt = dt - step;
				drop();
			} 
		}
	};

	function handle(action) {
		switch(action) {
			case DIR.LEFT:  move(DIR.LEFT);  break;
			case DIR.RIGHT: move(DIR.RIGHT); break;
			case DIR.UP:    rotate();        break;
			case DIR.DOWN:  drop();          break;
		}
	};

	function move(dir) {
		var x = current.x, y = current.y;
		switch(dir) {
			case DIR.RIGHT: x = x + 1; break;
			case DIR.LEFT:  x = x - 1; break;
			case DIR.DOWN:  y = y + 1; break;
		}
		if (unoccupied(current.type, x, y, current.dir)) {
			current.x = x;
			current.y = y;
			invalidate();
			return true;
		}
		else {
			return false;
		}
	};

	function rotate(dir) {
		var newdir = (current.dir == DIR.MAX ? DIR.MIN : current.dir + 1);
		if (unoccupied(current.type, current.x, current.y, newdir)) {
			current.dir = newdir;
			invalidate();
		}
	};

	function drop() {
		if (!move(DIR.DOWN)) {
			addScore(10);
			dropPiece();
			removeLines();
			setCurrentPiece(next);
			setNextPiece(randomPiece());
			clearActions();
			if (occupied(current.type, current.x, current.y, current.dir)) {
				lose();
			}
		}
	};

	function dropPiece() {
		eachblock(current.type, current.x, current.y, current.dir, function(x, y) {
			setBlock(x, y, current.type);
		});
	};

	function removeLines() {
		var x, y, complete, n = 0;
		for (y = ny ; y > 0 ; --y) {
			complete = true;
			for(x = 0 ; x < nx ; ++x) {
				if (!getBlock(x, y)) {
					complete = false;
				}
			}
			
			if (complete) {
				removeLine(y);
				y = y + 1; // recheck same line
				n++;
			}
		}
		if (n > 0) {
			addRows(n);
			addScore(100*Math.pow(2,n-1)); // 1: 100, 2: 200, 3: 400, 4: 800
		}
	};

	function removeLine(n) {
		var x, y;
		for (y = n ; y >= 0 ; --y) {
			for (x = 0 ; x < nx ; ++x) {
				setBlock(x, y, (y == 0) ? null : getBlock(x, y-1));
			}
		}
	};

	//rendering
	var invalid = {};

	function invalidate()         { invalid.court  = true; }
	function invalidateNext()     { invalid.next   = true; }
	function invalidateScore()    { invalid.score  = true; }
	function invalidateRows()     { invalid.rows   = true; }

	function draw() {
		ctx.save();
		ctx.lineWidth = 1;
		ctx.translate(0.5, 0.5); // for crisp 1px black lines
		drawCourt();
		drawNext();
		drawScore();
		drawRows();
		ctx.restore();
	};

	function drawCourt() {
		if (invalid.court) {
			ctx.clearRect(0, 0, canvas.width, canvas.height);
			if (playing) {
				drawPiece(ctx, current.type, current.x, current.y, current.dir);
			}
			var x, y, block;
			for(y = 0 ; y < ny ; y++) {
				for (x = 0 ; x < nx ; x++) {
					if (block = getBlock(x,y))
					drawBlock(ctx, x, y, block.color);
				}
			}
			ctx.strokeRect(0, 0, nx*dx - 1, ny*dy - 1); // court boundary
			invalid.court = false;
		}
	};

	function drawNext() {
		if (invalid.next) {
			var padding = (nu - next.type.size) / 2; // half-arsed attempt at centering next piece display
			uctx.save();
			uctx.translate(0.5, 0.5);
			uctx.clearRect(0, 0, nu*dx, nu*dy);
			drawPiece(uctx, next.type, padding, padding, next.dir);
			uctx.strokeStyle = 'black';
			uctx.strokeRect(0, 0, nu*dx - 1, nu*dy - 1);
			uctx.restore();
			invalid.next = false;
		}
	};

	function drawScore() {
		if (invalid.score) {
			html('score', ("00000" + Math.floor(vscore)).slice(-5));
			invalid.score = false;
		}
	};

	function drawRows() {
		if (invalid.rows) {
			html('rows', rows);
			invalid.rows = false;
		}
	};

	function drawPiece(ctx, type, x, y, dir) {
		eachblock(type, x, y, dir, function(x, y) {
			drawBlock(ctx, x, y, type.color);
		});
	};

	function drawBlock(ctx, x, y, color) {
		var grad1 = ctx.createRadialGradient(x*dx, y*dy, 0, x*dx, y*dy, dx);
		grad1.addColorStop(0, color);
		grad1.addColorStop(1, 'rgba(255, 255, 255, 1)');

		ctx.fillStyle = grad1;
		ctx.fillRect(x*dx, y*dy, dx, dy);
		ctx.strokeRect(x*dx, y*dy, dx, dy)
	};
	
	//line graph stuff
	$("#lineBtn").click(function() {
		$('#injectedInfo').remove();
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}
		resetCharts();
		getGraphData($('#startDate').val(), $('#endDate').val(), $('#measurementSelect').val(), "line");
	});

	//table stuff
	$("#tableBtn").click(function() {
		$('#injectedInfo').remove();
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}
		resetCharts();
		getTableData($('#startDate').val(), $('#endDate').val(), $('#measurementSelect').val());
	});

	function resetCharts() {
		//remove the old chart and table
		document.getElementById("chartDiv").innerHTML = "";
		var sampleTable = document.getElementById("tableView");
		if (sampleTable != null) {
			sampleTable.parentNode.removeChild(sampleTable);
		}
	}
	
	function getTableData(startDate, endDate, measure) {
		var sites = $("#site").val();
		
		var categorySelect = document.getElementById("categorySelect").value;
		
		var amountEnter = document.getElementById("amountEnter").value;
		var overUnderSelect = document.getElementById("overUnderSelect").value;
		var measurementSelect = document.getElementById("measurementSelect").value;
		
		$.ajax({
			type: "POST",
			url: "/WQIS/generic-samples/tableRawData",
			datatype: 'JSON',
			data: {
				'sites': sites,
				'startDate': startDate,
				'endDate': endDate,
				'category': categorySelect,
				'amountEnter': amountEnter,
				'overUnderSelect': overUnderSelect,
				'measurementSelect': measurementSelect
			},
			success: function(response) {
				//create the blank table
				var table = document.createElement("table");
				table.setAttribute("class", "table table-striped table-responsive");
				table.id = "tableView";
				
				var tableHeader = table.insertRow();
				
				Object.keys(response[0][0]).forEach(function(key) {
					if (!(key.includes("Exception") || key == "ID")) {
						var newCell = tableHeader.insertCell();
						newCell.innerText = key;
					}
				});
				
				//fill in each row
				for (var i=0; i<response[0].length; i++) {
					var newRow = table.insertRow();
					
					Object.keys(response[0][i]).forEach(function(key) {
					if (!(key.includes("Exception") || key == "ID")) {
						var newCell = newRow.insertCell();
						
						var textDiv = document.createElement('div');
						textDiv.setAttribute("class", "input text");
						newCell.appendChild(textDiv);
						
						var label = document.createElement('label');
						label.style = "display: table-cell; cursor: pointer; white-space:normal !important;";
						label.setAttribute("class", "btn btn-thin inputHide");
						label.setAttribute("for", key + "-" + i);
						label.innerText = response[0][i][key];
						
						label.onclick = function () {
							var label = $(this);
							var input = $('#' + label.attr('for'));
							input.trigger('click');
							label.attr('style', 'display: none');
							input.attr('style', 'display: in-line');
						};
						
						textDiv.appendChild(label);
						
						var cellInput = document.createElement("input");
						cellInput.type = "text";
						cellInput.name = key + "-" + i;
						cellInput.setAttribute("maxlength", 20);
						cellInput.size = 20;
						cellInput.setAttribute("class", "inputfields tableInput");
						cellInput.style = "display: none";
						cellInput.id = key + "-" + i;
						cellInput.setAttribute("value", response[0][i][key]);
						
						cellInput.onfocusout = (function () {
							var input = $(this);

							if (!input.attr('id')) {
								return;
							}

							var rowNumber = (input.attr('id')).split("-")[1];
							var sampleNumber = $('#Sample_Number-' + rowNumber).val();
	
							var parameter = (input.attr('name')).split("-")[0];
							var value = input.val();

							$.ajax({
								type: "POST",
								url: "/WQIS/generic-samples/updatefield",
								datatype: 'JSON',
								data: {
									'sampleNumber': sampleNumber,
									'parameter': parameter,
									'value': value
								},
								success: function () {
									var label = $('label[for="' + input.attr('id') + '"');

									input.attr('style', 'display: none');
									label.attr('style', 'display: in-line; cursor: pointer');

									if (value === '') {
										label.text('  ');
									}
									else {
										label.text(value);
									}
								},
								failure: function() {
									alert("failed");
								}
							});
						});
						
						textDiv.appendChild(cellInput);
					}
				});
				}
	
				document.getElementById("chartDiv").append(table);
			},
			fail: function(response) {
				alert("failed");
			},
			async: false
		});
	}

	function getGraphData(startDate, endDate, measure, graphType) {
		var sites = $("#site").val();
		
		//get all the selected checkboxes
		var measuresAll = [];
		
		var checkboxList = document.getElementById('checkboxList').getElementsByTagName('input');
		
		for (var k=0; k<checkboxList.length; k++) {
			if (checkboxList[k].checked == true) {
				measuresAll.push(checkboxList[k].value);
			}
		}
		
		//build the necessary canvases
		var chartDiv = document.getElementById("chartDiv");
		for (var k=0; k<measuresAll.length; k++) {
			var newCanvasContainer = document.createElement("div");
			newCanvasContainer.style = "width: 80%; text-align: center; margin: auto;";
			
			var newCanvas = document.createElement("canvas");
			newCanvas.id = "chart-" + k;
			newCanvasContainer.appendChild(newCanvas);
			chartDiv.appendChild(newCanvasContainer);
		}
		
		//get data and fill the charts in
		for (var k=0; k<measuresAll.length; k++) {
			$.ajax({
				type: "POST",
				url: "/WQIS/generic-samples/graphdata",
				datatype: 'JSON',
				data: {
					'sites': sites,
					'startDate': startDate,
					'endDate': endDate,
					'measure': measuresAll[k]
				},
				success: function(response) {
					//format that response
					
					function selectColor(colorIndex, palleteSize) {
						//returns color at an index of an evenly-distributed color pallete of arbitrary size
						if (palleteSize < 1) {
							palleteSize = 1; //defaults to one color, can't divide by zero or the universe implodes
						}
						
						return "hsl(" + (colorIndex * (360 / palleteSize) % 360) + ",100%,50%)";
					}
					
					var datasets = [];
					for (i=0; i<sites.length; i++) {
						var newDataset = {
							label: sites[i],
							borderColor: selectColor(i, sites.length),
							data: []
						};
						
						datasets.push(newDataset);
					}
					
					var labels = [];
					
					for (i=0; i<response[0].length; i++) {
						var newRow = []
						
						var date = response[0][i].date.split("T")[0];
						
						newRow.t = date;
						newRow.y = response[0][i].value;
						
						for (j=0; j<sites.length; j++) {
							if (response[0][i].site == sites[j]) {
								datasets[j].data.push(newRow);
								break;
							}
						}
						
						//make sure there isn't already a label created for this date, or things break in weird ways
						var found = false;
						for (j=0; j<labels.length; j++) {
							if (labels[j] == date) {
								found = true;
								break;
							}
						}
						if (found == false) {
							labels.push(date);
						}
					}
					
					var ctx = document.getElementById("chart-" + k).getContext("2d");

					var myChart = new Chart(ctx, {
						type: 'line',
						data: {
							labels: labels,
							datasets: datasets
						}
					});
				},
				async: false
			});
		}
	}
});