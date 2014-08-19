<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 25/12/13
 * Time: 12.13
 */
?>

<div class="smallbox" id="working">
<h2 class="menu_head">Menu</h2>
	<p class="menu_label class_icon">Amministrazione</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="#" class="_tab" id="tab_1">Scuola</a></li>
		<li><a href="#" class="_tab" id="tab_2">Utenti</a></li>
		<li><a href="#" class="_tab" id="tab_3">Classi</a></li>
		<li><a href="#" class="_tab" id="tab_4">Nuovo anno</a></li>
		<li><a href="#" class="_tab" id="tab_5">CDC</a></li>
		<li><a href="#" class="_tab" id="tab_6">Registri</a></li>
		<li><a href="#" class="_tab" id="tab_7">Scrutini</a></li>
		<li><a href="#" class="_tab" id="tab_8">Varie</a></li>
		<?php if (is_installed("com")): ?>
		<li><a href="#" class="_tab" id="tab_10">Moduli</a></li>
		<?php endif; ?>
		<?php if($admin_level == 0): ?>
		<li><a href="#" class="_tab" id="tab_9">Sviluppo</a></li>
		<?php endif; ?>
	</ul>
</div>
