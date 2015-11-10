

<section>
	<div class="left">

		<h2>Modules list</h2>
		<ul class="module-list">
			<li>
				<h3>Auth</h3> <?php 
				//Zend_Debug::dump($_POST['database_host']);
				/*
				 <p>Proin tempor congue tellus, nec fringilla felis ornare sed. Proin vel ligula nibh.
				Nulla in ligula velit. Suspendisse rutrum blandit pretium.</p>
                    <p><a href="#">Docs</a></p>*/ ?>
			</li>
			<li>
				<h3>Media</h3> <?php /*
                    <p>Proin tempor congue tellus, nec fringilla felis ornare sed. Proin vel ligula nibh.
                    Nulla in ligula velit. Suspendisse rutrum blandit pretium.</p>
                    <p><a href="#">Docs</a></p>*/ ?>
			</li>
			<li>
				<h3>Translation</h3>
				<p>Translation module allows you to have 2 languages on the portal :
					English and French.</p>
				<p>
					<a href="#">Docs</a>
				</p>
			</li>
			<li>
				<h3>Cms</h3>
				<p>Creating and editing your own pages with the CMS module.</p>
				<p>
					<a href="#">Docs</a>
				</p>
			</li>
			<li>
				<h3>Users</h3>
				<p>Managing users, groups and their access on the portal. Importing
					iTop's users through the Webservice.</p>
				<p>
					<a href="#">Docs</a>
				</p>
			</li>
			<li>
				<h3>Home</h3>
				<p>This module lists all Services you offer to your clients with
					iTop. You can manage their icons on the admin panel. Some
					statistics and an organisational chart to explain the services are
					present too.</p>
				<p>
					<a href="#">Docs</a>
				</p>
			</li>
			<li>
				<h3>Request</h3>
				<p>Create, consult and update your services and incidents requests.</p>
				<p>
					<a href="#">Docs</a>
				</p>
			</li>
			<li>
				<h3>Cron</h3>
				<p>You can launch cyclic commands through the cron on your
					application (synchronization with iTop for example).</p>
				<p>
					<a href="#">Docs</a>
				</p>
			</li>
		</ul>
	</div>
	<div class="right">
		<h2>Checklist</h2>
		<ul class="checklist">
			<?php
			foreach ($check->getCheckList() as $checkItem) :

			if ($checkItem['code'] == -1) {
                        $spanClass = 'ui-icon ui-icon-red ui-icon-alert';
                        $liClass = 'red';
                    } else if ($checkItem['code'] == 0) {
                        $spanClass = 'ui-icon ui-icon-red ui-icon-notice';
                        $liClass = 'orange';
                    } else {
                        if ($checkItem['canBeBetter'] == 1) {
                            $liClass = 'orange';
                            $spanClass = 'ui-icon ui-icon-red ui-icon-notice';
                        } else {
                            $spanClass = 'ui-icon ui-icon-bluelight ui-icon-check';
                            $liClass = '';
                        }
                    }

                    if ($checkItem['alt'] != '') {
                        $liClass .= ' tipsyauto';
                    }
                    ?>
			<li class="<?php echo $liClass ?>"
			<?php echo ($checkItem['alt'] != '')?' title="' . htmlentities($checkItem['alt']) . '"':''; ?>>
				<span class="<?php echo $spanClass; ?>"></span> <?php echo $checkItem['text']; ?>
			</li>
			<?php
			endforeach;
			?>
		</ul>
		<img src="./images/checklist.png" style="float: right;">
	</div>
	<div class="clear"></div>
	<?php

	if (!$check->hasError()): ?>
		<div class="middle">
			<h3>Your iTop's Portal installation seems to be good</h3>
	
			<p>Next step is to remove all this installation page.</p>
			<input type="button" class="ui-button ui-button-bg-white" name="btn_cancel"	value="Back" onclick="navigate('prev')">
			<!--<input type="button" class="ui-button ui-button-text-only ui-button-bg-white" value="Cancel" name="btn_cancel" id="btn_cancel" onclick="cancel();">-->
		
			</a> <a class="ui-button ui-button-text-only ui-button-bg-white"
				href="?removeMe=true"> <span class="ui-button-text">Remove
					installation file.</span>
			</a>
		</div>
	<?php else: ?>
	<div class="middle">
		<h3>Your iTop's Portal installation is not good</h3>
		<p>What sould you do ?</p>
		<a class="ui-button ui-button-text-only ui-button-bg-white"
			href="" onclick="navigate('prev')"> <span class="ui-button-text">Go
				back</span>
		</a>
		<!-- <a class="ui-button ui-button-text-only ui-button-bg-white" href="http://wiki.centurion-project.org/">
                    <span class="ui-button-text">Check the wiki</span>
                </a>
                <a class="ui-button ui-button-text-only ui-button-bg-white" href="http://groups.google.com/group/centurion-project">
                    <span class="ui-button-text">Use the Centurion Google Group to find help</span>
                </a>-->
	</div>
	<?php endif; ?>

	<div class="bottom">
		<h2>Documentations</h2>
		<ul class="doc-list">
			<li class="doc-list-1">
				<ul>
					<li class="doc-list-2"><a href="">Configure this portal to work
							with Ldap</a>
						<p>Coming soon.</p></li>
					<li class="doc-list-2"><a href="">The Services Interface
							Configuration</a>
						<p>Coming soon.</p></li>
				</ul>
			</li>
			<li class="doc-list-1">
				<ul>
					<li class="doc-list-2"><a href="">Cron</a>
						<p>Synchronize some datas from iTop, coming soon.</p></li>
					<li class="doc-list-2"><a href="">Auth</a>
						<p>Centurion provides by default an authentication API that helps
							you manage users. in fact this is a built-in module that you can
							find in library/Centurion/Contrib/auth folder.</p></li>
				</ul>
			</li>
			<li class="doc-list-1">
				<ul>
					<li class="doc-list-2"><a href="">Translation trait</a>
						<p>A little "How To" to implement the translation module in your
							module</p></li>
					<li class="doc-list-2"><a href="">Other Questions</a>
						<p>Coming soon.</p></li>
				</ul>
			</li>
		</ul>
		<div class="clear"></div>
	</div>