<?php
namespace ExternalModules;

class StickyMatrixHeadersExternalModule extends AbstractExternalModule {
	function hook_survey_page() {
		?>
		<script>
			$(function(){
				$('.headermatrix').each(function(){
					var header = $(this)
					var form = header.closest('form')
					
					var floatingHeader = $('<div></div>').append(header.clone())

					floatingHeader.css({
						position: 'fixed',
						display: 'none',
						top: '0px',
						left: form.position().left,
						width: form.width(),
						background: '#f3f3f3',
						'border-bottom': '1px solid #dddddd',
						'padding-bottom': '5px',
						'padding-left': form.width() - header.width()
					})

					var setFloatingHeaderWidth = function(){
						floatingHeader.css({
							left: form.position().left,
							width: form.width(),
							'padding-left': form.width() - header.width()
						})
					}

					setFloatingHeaderWidth()
					$(window).on('resize', function(){
					    setFloatingHeaderWidth()
					})

					$('body').append(floatingHeader)

					$(window).scroll(function(){
						var scrollTop = $(window).scrollTop()
						var headerTop = header.position().top

						if(scrollTop < headerTop){
							floatingHeader.css({
								display: 'none'
							})
						}
						else if(scrollTop >= headerTop){
							var matrixGroup = header.closest('tr').attr('mtxgrp')
							var lastRow = $('tr[mtxgrp=' + matrixGroup + ']:visible').last()
							var lastRowTop = lastRow.position().top - floatingHeader.height()

							var top
							if(scrollTop < lastRowTop ){
								top = 0
							}
							else{
								top = lastRowTop - scrollTop
							}

							floatingHeader.css({
								display: 'block',
								top: top + 'px'
							})
						}
					})
				})
			})
		</script>
		<?php
	}
}
