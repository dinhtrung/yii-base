<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
    <div id="content" class="span9">
        <?php echo $content; ?>
    </div>
        <div id="sidebar" class="span3">
        <?php
            $this->widget('bootstrap.widgets.TbNav', array(
                'items'=>$this->menu,
				'type' => TbHtml::NAV_TYPE_TABS,
				'stacked' => TRUE,
            ));
        ?>
        </div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>
