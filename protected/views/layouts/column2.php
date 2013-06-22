<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="span-19">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-5 last">
		<div id="sidebar">
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Operations',
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->menu,
				'activeCssClass'	=>	'active',
				'htmlOptions'=>array('class'=>'operations'),
			));
			$this->endWidget();

			if (! empty($this->page["sidebar"])) print $this->page["sidebar"];

		?>


		</div><!-- sidebar -->
	</div>
</div>
<?php $this->endContent(); ?>