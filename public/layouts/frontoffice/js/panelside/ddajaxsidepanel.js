/* Ajax Side Panel script (v1.0)
* Created: May 1st, 2012. This notice must stay intact for usage 
* Author: Dynamic Drive at http://www.dynamicdrive.com/
* Visit http://www.dynamicdrive.com/ for full source code
*/

var ddajaxsidepanel = {
	defaults: {ajaxloadgif:'squareloading.gif', fx:{dur:'normal', easing:'swing'}, openamount:'80%', openamount_minthreshold:'400px'},

	$panelref: null,
	$contentarea: null,
	setting: null,
	docdimensions: null,
	paneldimensions: null,
	$ajaxloadgif: null,
	delaytimer: null,

	loadcontent: function(url, type){
		var $ = jQuery
		if (url == null){
			this.$contentarea.empty()
			this.$panelref.data('state', 'closed')
		}
		else{
			this.$panelref.data('state', 'open')		
			this.$contentarea.html(this.$ajaxloadgif)
			if (type){
				loadtype = type
			}
			else if (url.indexOf('http') != -1){
				var url_rootdomain = url.match(/^http[^:]*:\/\/((?:www\.){0,1}([^\/]+))/i) // match domain name portion of http link
				if (url_rootdomain && location.hostname.indexOf(RegExp.$2)!=-1){ //if URL's root domain (without www) matches current doc's hostname, meaning it's a internal URL (ie: http://mysite.com/page1.htm)
				document.write = document.writeLn = function(){} // overwrite default document.write() function, as it causes major problems if present inside Ajax fetched document
					url = url.replace(RegExp.$1, location.hostname)
					loadtype = "ajax"
				}		
				else{
					loadtype = "iframe"
				}		
			}
			else{
				loadtype = "ajax"
			}
			if (loadtype == "iframe"){
				ddajaxsidepanel.$contentarea.html('<iframe src="javascript:false" style="border:0; margin:0; padding:0; width:100%; height:100%"></iframe>')
				ddajaxsidepanel.$contentarea.find('iframe:eq(0)').attr('src', url)
			}
			else{
				ddajaxsidepanel.$contentarea.load(url)
			}
		}
	},

	showhidepanel:function(url, action, type){
		var $ = jQuery, setting = this.setting
		var panelstate =  this.$panelref.data('state')
		var winwidth = $(window).width(), panelwidth = this.$panelref.outerWidth()
		if (panelwidth < parseInt(setting.openamount_minthreshold))
			return true
		if (setting.openamount_maxwidth && setting.openamount_maxwidth > setting.openamount_minthreshold)
			panelwidth = Math.min(panelwidth, parseInt(setting.openamount_maxwidth))
		if (action =="show" && panelstate == "open")
			this.$panelref.animate({left: '+=50'}, function(){
				ddajaxsidepanel.loadcontent(null)
			})
		var finalcss = (action=="show")? {left: winwidth-panelwidth, opacity: 1} : {left: winwidth, opacity: 0}
		this.$panelref.animate(finalcss, setting.fx.dur, (this.$panelref.data('state')=='open')? 'easeOutBack' : setting.fx.easing, function(){
			ddajaxsidepanel.loadcontent(url, type)
		})
		return false
	},

	

	init: function(setting){
		var $ = jQuery
		this.setting = $.extend({}, this.defaults, setting)
		if (setting.targetselector){
			var $targetlinks = $(setting.targetselector).each(function(){ // seek out targeted selectors on the page
				var $el = $(this)
				$el.on('click', function(){
					return ddajaxsidepanel.showhidepanel(this.href, "show", this.getAttribute('data-loadtype'))
				})
			})
		}
		if (this.$panelref){ // if ajax content panel already defined, just exit
			return
		}
		this.$ajaxloadgif = $('<table width="100%" height="100%" align="center" valign="center" style="text-align:center"><tr><td><img src="' + this.setting.ajaxloadgif + '"/></td></tr></table>')
		this.$panelref = $('<div class="ddajaxsidepanel"><div class="panelhandle"></div><div class="contentarea"></div></div>').appendTo(document.body)
		this.$panelref
			.css({width:this.setting.openamount, height:'100%', left:'100%', visibility:'visible', opacity:0})
			.data('state', 'closed')
		this.$contentarea = this.$panelref.find('div.contentarea:eq(0)')
			.click(function(e){
				e.stopPropagation()
			})
		this.$panelref.find('div.panelhandle:eq(0)')
			.attr('title', 'Close Content Panel')
			.on('click', function(){
				ddajaxsidepanel.showhidepanel(null, "hide")
			})
		$(document).on('click', function(e){
			if (e.which == 1) // if left click on mouse
				ddajaxsidepanel.showhidepanel(null, "hide")
		})
		this.paneldimensions = {w: this.$panelref.outerWidth(), h: this.$panelref.outerHeight()}
	}

}

jQuery.extend(jQuery.easing, {  //see http://gsgd.co.uk/sandbox/jquery/easing/
	easeOutBack:function(x, t, b, c, d, s){
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	}
})


// Initialize Ajax Side Panel script:

jQuery(function(){
	ddajaxsidepanel.init({
		targetselector: 'a[rel="ajaxpanel"]',
		ajaxloadgif: 'squareloading.gif', //full path to "loading" gif relative to document. When in doubt use absolute URL to image.
		fx: {dur:500, easing: 'easeInQuad'}, // dur: duration of slide effect (milliseconds), easing: 'ease_in_type_string'
		openamount:'80%', // Width of panel when fully opened (Percentage value relative to page, or pixel value
		openamount_minthreshold:'400px' //Minimum required width of panel (when fully opened)  before panel is shown. This prevents panel from being shown on small screens or devices.
	})
});