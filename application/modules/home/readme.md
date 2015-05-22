My Module
=========

Name : mymodule
Author : Mathias Desloges (md@centurion-project.org)

Description :
-------------

This module is not a real module, it's a starter kit to help developer focusing on what matters.
in fact this module is a default module skeleton it is intended to avoid errors that may append 
if you create the whole files/folders tree by hand


Usage :
-------

As evry module, before using it, you must add the folowing line to your config file named modules.ini 
and located in the 'CENTURION_PATH/config' folder

` resources.modules[] = "mymodule" `


Once this is done, you may find a default controller named IndexController in the controller folder
and the associated views has been created to. so you can directly type the folowing url in your browser

` http://yourdomain.com/mymodule/index/index `

and you should see a the default page provided by your module.
