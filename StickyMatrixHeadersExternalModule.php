<?php
namespace Vanderbilt\StickyMatrixHeadersExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

class StickyMatrixHeadersExternalModule extends AbstractExternalModule {
	function hook_survey_page() {
		?>
		<script>
			$(function(){
				// each matrix in the instrument will have an object in matrices array like:
				// {header: _, floatingHeader: _, form: _}
				var matrices = []
				
				// create floating headers
				$('.headermatrix').each(function(){
					var header = $(this)
					var form = header.closest('form')
					var floatingHeader = $('<div></div>').append(header.clone())
					
					matrices.push({
						"header": header,
						"form": form,
						"floatingHeader": floatingHeader
					})
					
					floatingHeader.css({
						position: 'fixed',
						display: 'none',
						top: '-5px',
						left: form.position().left,
						width: form.width(),
						background: '#f3f3f3',
						'border-bottom': '1px solid #dddddd',
						'padding-bottom': '5px',
						'padding-left': form.width() - header.width()
					})

					$('body').append(floatingHeader)
				})
				
				var setFloatingHeaderWidth = function(matrix){
					matrix.floatingHeader.css({
						left: matrix.form.position().left,
						width: matrix.form.width(),
						'padding-left': matrix.form.width() - matrix.header.width()
					})
				}
				
				// go ahead and set widths for each floating header
				for(i=0;i<matrices.length;i++){
					setFloatingHeaderWidth(matrices[i])
				}
				
				// and whenever window resizes
				$(window).on('resize', function(){
					for(i=0;i<matrices.length;i++){
						setFloatingHeaderWidth(matrices[i])
					}
				})
				
				// decide when to show each floating header based on scroll
				$(window).scroll(function(){
					for (i = 0; i < matrices.length; i++){
						var header = matrices[i].header
						var floatingHeader = matrices[i].floatingHeader
						var form = matrices[i].form
						
						var scrollTop = $(window).scrollTop()
						var headerTop = header.offset().top
						
						var matrixGroup = header.closest('tr').attr('mtxgrp')
						var lastRow = $('tr[mtxgrp=' + matrixGroup + ']:visible').last()
						var lastRowTop = lastRow.offset().top
						
						if (scrollTop > headerTop && scrollTop <= lastRowTop){
							let top = 0
							if (scrollTop > (lastRowTop - floatingHeader.height())) top = -(scrollTop - (lastRowTop - floatingHeader.height()) + 2)		// + 2 to prevent floating header from overlapping last row
							
							floatingHeader.css({
								display: 'block',
								top: top + 'px'
							})
						} else {
							floatingHeader.css({
								display: 'none'
							})
						}
					}
				})
			})
		</script>
		<?php
	}
}
