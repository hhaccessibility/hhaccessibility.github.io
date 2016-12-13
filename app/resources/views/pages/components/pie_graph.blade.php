<div class="pie-graph{{ isset($size) && $size === 'big' ? ' big' : '' }}"
	data-component='"percent": {{ $percent }}{{ isset($size) && $size === "big" ? ', "size": "big"' : '' }}'>
	<canvas></canvas>
</div>