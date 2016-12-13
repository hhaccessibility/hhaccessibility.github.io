
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

function drawShape(g, shape, w, h)
{
	if (shape.type === 'arc') {
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
	{'type': 'disk', 'colour': '#bebec0', 'radius': diskRadius},
	{'type': 'arc', 'colour': '#267ac2', 'radius': diskRadius + 1, 'angle': angle},
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
	{'type': 'disk', 'colour': '#bebec0', 'radius': ( w / 2 ) -1},
	{'type': 'arc', 'colour': '#267ac2', 'radius': ( w / 2 ), 'angle': (percent / 50.0) * Math.PI},
	{'type': 'disk', 'colour': '#ddd', 'radius': ( w / 2 ) * 0.75},
	{'type': 'disk', 'colour': '#555', 'radius': ( w / 2 ) * 0.65},
	];
	shapes.forEach(function(shape) {
		drawShape(g, shape, w, h);
	});
	
	g.textAlign="center";
	g.textBaseline="middle";
	g.fillStyle = '#fff';
	g.font = (w * 0.28) + "px Arial";
	g.fillText(percent + "%", w / 2, h / 2);
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