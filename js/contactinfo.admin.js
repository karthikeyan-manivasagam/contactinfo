/**
 * @file
 * JS for the Contact Information module settings form.
 */
(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.contactinfo = {
    attach: function () {
      var contactInfoProperties = {
        org: {
          $checkbox: $('#edit-contactinfo-use-site-name'),
          $textField: $('#edit-contactinfo-org'),
          siteSettingsVal: drupalSettings.contactinfo.sitename
        },
        tagline: {
          $checkbox: $('#edit-contactinfo-use-site-slogan'),
          $textField: $('#edit-contactinfo-tagline'),
          siteSettingsVal: drupalSettings.contactinfo.siteslogan
        }
      }

      $.each(contactInfoProperties, function (i, v) {
        // Store user-entered value.
        var userEnteredVal = v.$textField.val();
        if (v.$checkbox.is(':checked')) {
          v.$textField.attr('disabled', 'disabled').val(v.siteSettingsVal);
        }
        v.$checkbox.change(function () {
          if ($(this).is(':checked')) {
            // Store latest user-entered value in case the checkbox gets unchecked.
            userEnteredVal = v.$textField.val();
            v.$textField.attr('disabled', 'disabled').val(v.siteSettingsVal);
          }
          else {
            v.$textField.removeAttr('disabled').val(userEnteredVal);
          }
        });
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
