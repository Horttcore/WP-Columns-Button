(function() {
	tinymce.create('tinymce.plugins.tinyMCEColumnButton', {

		init : function(ed, url) {
			/*
			// Replace shortcode before editor content set
			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = this._do_spot(o.content);
			});
			
			// Replace shortcode as its inserted into editor (which uses the exec command)
			ed.onExecCommand.add(function(ed, cmd) {
				if (cmd === 'mceInsertContent'){
					tinyMCE.activeEditor.setContent( this._do_spot(tinyMCE.activeEditor.getContent()) );
				}
			});

			// Replace the image back to shortcode on save
			ed.onPostProcess.add(function(ed, o) {
				if (o.get)
					o.content = this._get_spot(o.content);
			});
			*/
			
			// Add button
			ed.addButton('tinymce_column_button', {
				title : 'Column Split',
				image : url+'/../images/btn-column.png',
				onclick : function() {
					ed.execCommand('mceInsertContent', false, '[COLUMN_SPLIT]');
				}
			});
		},
		/*
		_do_spot : function(co) {
			return co.replace('[COLUMN_SPLIT]', '<div class="tinymce-column-split"></div>' );
		},

		_get_spot : function(co) {
			return co.replace( '<div class="tinymce-column-split"></div>', '[COLUMN_SPLIT]' );
		},
		*/

		getInfo : function() {
			return {
				longname : "WordPress Column Split",
				author : 'Ralf Hortt',
				authorurl : 'http://horttcore.de/',
				infourl : 'http://horttcore.de/',
				version : "1.0"
			};
		}

	});
	tinymce.PluginManager.add('tinymce_column_button', tinymce.plugins.tinyMCEColumnButton);
})();