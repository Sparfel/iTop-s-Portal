<section>
	<div class="left">

		<p>Before to enjoy this portal, we have to verify and to configure few
			things. The server needs some modules and some specifications to
			work, take a look on the check List on the right.</p>
		<h2>Prerequisites :</h2>
		<ul class="module-list">
			<li><h3>Virtual Host</h3>
				<p>This portal uses virtual hosting. It must be declared in your
					Apache configuration file. It may look like :
				
				<p>
				
				<pre>
&lt;VirtualHost *:80&gt;
	ServerName itop.portal.local
	DocumentRoot 'path_to_portal_directory/public/'
	DirectoryIndex index.php
	&lt;Directory 'path_to_portal_directory'&gt;
		Options Indexes FollowSymLinks -MultiViews
		AllowOverride All
        Order allow,deny
		Allow from all
    &lt;/Directory&gt;
&lt;/VirtualHost&gt;
		</pre></li>

			<li>
				<h3>A MySQL Database</h3>
				<p>The iTop's Portal needs a Database. Please create a MySQL
					Database first on your Server, the Portal needs it to store
					informations. We will ask you the credentials to connect to it.</p>
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

	<div class="middle">
		<h3>Next Step : the database configuration file.</h3>
		<p>We will create the configuration files to connect to the database
			and we will populate it. Let's go ...</p>
		<a class="ui-button ui-button-text-only ui-button-bg-white"
			href="?step=1" onclick="document.forms['database'].submit();"> <span
			class="ui-button-text">Next</span>
		</a>
	</div>

	<div class="bottom">


		<div class="clear"></div>
	</div>