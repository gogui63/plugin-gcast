<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('gcast');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>
<div class="row row-overflow">
  <div class="col-lg-2">
    <div class="bs-sidebar">
      <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
        <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un dispositif}}</a>
        <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
        <?php
foreach ($eqLogics as $eqLogic) {
	echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
}
?>
     </ul>
   </div>
 </div>
 <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
   <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
   <div class="eqLogicThumbnailContainer">
    <div class="cursor eqLogicAction" data-action="add" style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
      <i class="fa fa-plus-circle" style="font-size : 5em;color:#94ca02;"></i>
    <br>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;;color:#94ca02">{{Ajouter}}</span>
  </div>
  <div class="cursor" id="bt_healthgcast" style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
      <i class="fa fa-medkit" style="font-size : 5em;color:#767676;"></i>
    <br>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">{{Santé}}</span>
  </div>
</div>
<legend><i class="icon techno-cable1"></i>  {{Mes gcasts}}
</legend>
<div class="eqLogicThumbnailContainer">
  <?php
foreach ($eqLogics as $eqLogic) {
	$opacity = '';
	if ($eqLogic->getIsEnable() != 1) {
		$opacity = 'opacity:0.3;';
	}
	echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
	echo '<img src="' . $plugin->getPathImgIcon() . '" height="105" width="105" />';
	echo "<br>";
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogic->getHumanName(true, true) . '</span>';
	echo '</div>';
	$url = network::getNetworkAccess('external') . '/plugins/gcast/core/php/gcastApi.php?apikey=' . jeedom::getApiKey('gcast') . '&id=' . $eqLogic->getId();
}
?>
</div>
</div>
<div class="col-lg-10 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
  <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
  <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
  <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
  <a class="btn btn-default eqLogicAction pull-right" data-action="copy"><i class="fa fa-copy"></i> {{Dupliquer}}</a>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
    <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
    <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
  </ul>
  <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
    <div role="tabpanel" class="tab-pane active" id="eqlogictab">
      <br/>
      <form class="form-horizontal">
        <fieldset>
          <div class="form-group">
            <label class="col-lg-3 control-label">{{Nom de l'équipement}}</label>
            <div class="col-lg-4">
              <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
              <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label" >{{Objet parent}}</label>
            <div class="col-lg-4">
              <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                <option value="">{{Aucun}}</option>
                <?php
foreach (object::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
             </select>
           </div>
         </div>
         <div class="form-group">
          <label class="col-lg-3 control-label">{{Catégorie}}</label>
          <div class="col-lg-9">
            <?php
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
	echo '<label class="checkbox-inline">';
	echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
	echo '</label>';
}
?>
         </div>
       </div>
       <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-9">
          <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
          <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">{{Adresse IP}}</label>
        <div class="col-lg-4">
          <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="addr" placeholder="{{Adresse IP}}"/>
        </div>
      </div>
	  <div class="form-group">
    <label class="col-lg-3 control-label">{{URL de retour}}</label>
    <div class="alert alert-warning col-lg-6">
        <span><?php echo network::getNetworkAccess('external') . '/plugins/gcast/core/php/gcastApi.php?apikey=' . config::byKey('api', 'gcast') . '&id=#ID_EQUIPEMENT#&query=XXXX';?></span>
    </div>
	</div>
	<div class="form-group">
	<label class="col-lg-3 control-label">{{Moteur TTS:}}</label>
                    		<div class="col-lg-3">
                        	<select id="moteurtts" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="moteurtts" onchange="if(this.selectedIndex == 0){ document.getElementById('optionpico').style.display = 'block';document.getElementById('optiongoogle').style.display = 'none'}
						else { document.getElementById('optionpico').style.display = 'none'; document.getElementById('optiongoogle').style.display = 'block'}">
								<option value="picotts">{{PicoTTS}}</option>
								<option value="google">{{Google}}</option>
							</select>
                    		</div>
                    <div id="optionpico">
					<br/><br/><br/>
                     <label class="col-lg-3 control-label">{{Voix PicoTTS:}}</label>
                    		<div class="col-lg-3">
                        	<select id="picoopt" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="picovoice">
								<option value="fr-FR">{{Français}}</option>
                                <option value="de-DE">{{Allemand}}</option>
                                <option value="en-US">{{Américain}}</option>
                                <option value="en-GB">{{Anglais}}</option>
                                <option value="es-ES">{{Espagnol}}</option>
								<option value="it-IT">{{Italien}}</option>
							</select>
                    		</div>
                     </div>
					 <div id="optiongoogle">
					<br/><br/><br/>
					<label class="col-lg-3 control-label">{{Voix Google:}}</label>
                    		<div class="col-lg-3">
                        	<select id="googleopt" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="googlevoice">
								<option value="fr">Français</option>
	                            <option value="af">Afrikaans</option>
	                            <option value="sq">Albanian</option>
	                            <option value="ar">Arabic</option>
	                            <option value="hy">Armenian</option>
	                            <option value="ca">Catalan</option>
	                            <option value="zh-CN">Mandarin (simplified)</option>
	                            <option value="zh-TW">Mandarin (traditional)</option>
	                            <option value="hr">Croatian</option>
	                            <option value="cs">Czech</option>
	                            <option value="da">Danish</option>
	                            <option value="nl">Dutch</option>
	                            <option value="en">English</option>
	                            <option value="en-us">English (United States)</option>
	                            <option value="en-au">English (Australia)</option>
	                            <option value="eo">Esperanto</option>
	                            <option value="fi">Finnish</option>
	                            <option value="de">German</option>
	                            <option value="el">Greek</option>
	                            <option value="ht">Haitian Creole</option>
	                            <option value="hi">Hindi</option>
	                            <option value="hu">Hungarian</option>
	                            <option value="is">Icelandic</option>
	                            <option value="id">Indonesian</option>
	                            <option value="it">Italian</option>
	                            <option value="ja">Japanese</option>
	                            <option value="ko">Korean</option>
	                            <option value="la">Latin</option>
	                            <option value="lv">Latvian</option>
	                            <option value="mk">Macedonian</option>
	                            <option value="no">Norwegian</option>
	                            <option value="pl">Polish</option>
	                            <option value="pt">Portuguese</option>
	                            <option value="ro">Romanian</option>
	                            <option value="ru">Russian</option>
	                            <option value="sr">Serbian</option>
	                            <option value="sk">Slovak</option>
	                            <option value="es">Spanish</option>
	                            <option value="sw">Swahili</option>
	                            <option value="sv">Swedish</option>
	                            <option value="ta">Tamil</option>
	                            <option value="th">Thai</option>
	                            <option value="tr">Turkish</option>
	                            <option value="vi">Vietnamese</option>
	                            <option value="cy">Welsh</option>
							</select>
                    		</div>
                     </div>
	</div>
    </fieldset>
  </form>
</div>
<div role="tabpanel" class="tab-pane" id="commandtab">
 <table id="table_cmd" class="table table-bordered table-condensed">
   <thead>
    <tr>
      <th>{{Nom}}</th><th>{{Options}}</th><th>{{Action}}</th>
    </tr>
  </thead>
  <tbody>

  </tbody>
</table>
</div>
</div>
</div>
</div>

<?php include_file('desktop', 'gcast', 'js', 'gcast');?>
<?php include_file('core', 'plugin.template', 'js');?>
