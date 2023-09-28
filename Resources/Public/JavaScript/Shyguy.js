// we ignore RTE fields, because CKeditor5 has a built-in shy-plugin
document.addEventListener("DOMContentLoaded", function () {
  var insertSoftHyphenLinks = document.querySelectorAll('a[href="#insertSoftHyphen"]');
  // Insert ↵ glyph to active element by clicking
  if (insertSoftHyphenLinks.length) {
    insertSoftHyphenLinks.forEach(function (link) {
      link.addEventListener("mousedown", function (e) {
        e.preventDefault();

        var activeElement = document.activeElement;
        var activeElementValue = activeElement.value || "";
        var activeElementSelection = getCaretPosition(activeElement);

        activeElementValue = replaceRange(activeElementValue, activeElementSelection.start, activeElementSelection.end, '↵');
        activeElement.value = activeElementValue;
        activeElement.dispatchEvent(new Event('change'));
        activeElement.dispatchEvent(new Event('keyup'));
      });
    });
  }

  replaceDomGlyphs();
});

// Replace Existing ↵ with &shy; glyph in input fields and text areas
function replaceDomGlyphs() {
  var inputFields = document.querySelectorAll('input, .form-wizards-element textarea[id^="formengine-textarea-"]');
  inputFields.forEach(function (inputField) {
    inputField.value = inputField.value.replace(/(\&shy;|\­)/gi, "↵");
  });
}

function getCaretPosition(ctrl) {
  // IE < 9 Support
  if (document.selection) {
    ctrl.focus();
    var range = document.selection.createRange();
    var rangelen = range.text.length;
    range.moveStart('character', -ctrl.value.length);
    var start = range.text.length - rangelen;
    return {
      'start': start,
      'end': start + rangelen
    };
  } // IE >=9 and other browsers
  else if (ctrl.selectionStart || ctrl.selectionStart == '0') {
    return {
      'start': ctrl.selectionStart,
      'end': ctrl.selectionEnd
    };
  } else {
    return {
      'start': 0,
      'end': 0
    };
  }
}

function replaceRange(s, start, end, substitute) {
  return s.substring(0, start) + substitute + s.substring(end);
}
