
function fillDisk(g, cx, cy, radius, colour)
{
	g.fillStyle = colour;
	g.beginPath();
	g.arc(cx, cy, radius, 0, 2 * Math.PI);
	g.fill();
	g.closePath();
}

function fillArc(g, cx, cy, radius, colour, angle)
{
	var initAngle = - Math.PI / 2;
	g.fillStyle = colour;
	g.beginPath();
	g.moveTo(cx, cy);
	
	g.arc(cx, cy, radius, initAngle, initAngle + angle);
	g.fill();
	g.closePath();
}

function drawArc(g, cx, cy, radius, colour, angle)
{
	var drawLinesToCentre = (angle < Math.PI * 2 - 0.0001);
	var initAngle = - Math.PI / 2;
	g.strokeStyle = colour;
	g.beginPath();
	if (drawLinesToCentre) {
		g.moveTo(cx, cy);
	}
	g.arc(cx, cy, radius, initAngle, initAngle + angle);
	if (drawLinesToCentre) {
		g.lineTo(cx, cy);
	}
	g.stroke();
	g.closePath();
}

function drawShape(g, shape, w, h)
{
	if (shape.type === 'arc-outline') {
		drawArc(g, w / 2, h / 2, shape.radius, shape.colour, shape.angle);
	}
	else if (shape.type === 'arc') {
		fillArc(g, w / 2, h / 2, shape.radius, shape.colour, shape.angle);
	}
	else {
		fillDisk(g, w / 2, h / 2, shape.radius, shape.colour);
	}
}

function drawBigGraph(g, w, h, percent)
{
	var diskRadius = ( w / 2 ) - 1;
	var angle = (percent / 50.0) * Math.PI;
	var shapes = [
	{'type': 'disk', 'colour': '#666', 'radius': diskRadius},
	{'type': 'disk', 'colour': '#ccc', 'radius': diskRadius * 0.93},
	{'type': 'arc', 'colour': '#fff', 'radius': diskRadius * 0.95, 'angle': angle},
	];
	shapes.forEach(function(shape) {
		drawShape(g, shape, w, h);
	});

	angle -= Math.PI / 2;
	g.strokeStyle = '#fff';
	g.beginPath();
	g.moveTo(w / 2, 0);
	g.lineTo(w / 2, h / 2);
	g.lineTo(w / 2 + diskRadius * Math.cos(angle), h / 2 + diskRadius * Math.sin(angle));
	g.stroke();
	g.closePath();
}

function drawNormalGraph(g, w, h, percent)
{
	var shapes = [
	{'type': 'disk', 'colour': '#bbb', 'radius': ( w / 2 ) -1},
	{'type': 'arc', 'colour': '#566480', 'radius': ( w / 2 -1 ), 'angle': (percent / 50.0) * Math.PI},
	{'type': 'arc-outline', 'colour': '#000', 'radius': ( w / 2 -1), 'angle': (percent / 50.0) * Math.PI},
	];
	shapes.forEach(function(shape) {
		drawShape(g, shape, w, h);
	});
}

function initGraph(divElement, componentData)
{
	if( typeof componentData === 'string' ) {
		componentData = JSON.parse('{' + componentData + '}');	
	}
	else {
		throw new Error('componentData must be a string.');
	}
	
	var c = divElement.querySelectorAll('canvas')[0];
	var g = c.getContext('2d');
	var size = 40;
	if (componentData.size === 'big')
		size = 43;

	var w = c.width = c.innerWidth = size;
	var h = c.height = c.innerHeight = size;

	if (componentData.size === 'big')
		drawBigGraph(g, w, h, componentData.percent);
	else
		drawNormalGraph(g, w, h, componentData.percent);
}

function processPieGraphs()
{
	var pieGraphs = document.body.querySelectorAll('.pie-graph');
	for (var i=0 ; i < pieGraphs.length ; i++) {
		var pieGraphElement = pieGraphs[i];
		var componentData = pieGraphElement.dataset.component;
		initGraph(pieGraphElement, componentData);
	}
}

document.addEventListener("DOMContentLoaded", function(event) {
    processPieGraphs();
});