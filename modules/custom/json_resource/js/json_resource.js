(function ($, Drupal, drupalSettings) {
  'use strict';

  Drupal.behaviors.jsonResourceEditor = {
    attach: function (context, settings) {
      // Find JSON editor element
      $(context).find('[data-json-editor-wrapper=1]').once('jsonResourceEditor').each(function (idx) {
        const editorEl = $(this).find('[data-json-editor=1]');
        const downloadBtn = $(this).find('[data-json-editor-download=1]');
        let textArea = null;

        let jsonAttr = editorEl.data();

        if (typeof jsonAttr.drupalSelector !== 'undefined') {
          const editorDrupalSelector = jsonAttr.drupalSelector;
          const textAreaDrupalSelector = editorDrupalSelector.substr(0,
              editorDrupalSelector.length - 'json-editor'.length) + 'value';
          textArea = $(context).find('[data-drupal-selector=' + textAreaDrupalSelector + ']');

          // Sync text between editor and text area
          jsonAttr.jsonEditorOptions.onChangeText = function (jsonString) {
            textArea.val(jsonString)
          }
        }

        let editor = new JSONEditor(editorEl[0], jsonAttr.jsonEditorOptions);

        // Set JSON
        if (textArea !== null) {
          editor.setText(textArea.val());
        } else {
          if (typeof jsonAttr.jsonEditorValue !== 'undefined') {
            if (typeof jsonAttr.jsonEditorValue === 'string') {
              editor.setText(jsonAttr.jsonEditorValue);
            } else {
              editor.set(jsonAttr.jsonEditorValue);
            }
          }
        }

        downloadBtn.on('click', function () {
          // Save Dialog
          var fname = window.prompt("Save as...");

          // Check json extension in file name
          if (fname.indexOf(".") == -1) {
            fname = fname + ".json";
          } else {
            if (fname.split('.').pop().toLowerCase() == "json") {
              // Nothing to do
            } else {
              fname = fname.split('.')[0] + ".json";
            }
          }

          let blob = new Blob([editor.getText()], {type: 'application/json;charset=utf-8'});
          saveAs(blob, fname);
        });
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
