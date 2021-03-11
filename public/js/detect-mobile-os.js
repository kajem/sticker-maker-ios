/**
 * Determine the mobile operating system.
 * This function returns one of 'iOS', 'Android', 'Windows Phone', or 'unknown'.
 *
 * @returns {String}
 */
function getMobileOperatingSystem() {
    var androidStoreURL = 'https://play.google.com/store/apps/developer?id=Brain+Craft+Limited';
    var iosStoreURL = 'https://apps.apple.com/us/app/id1505991796';
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;

        // Windows Phone must come first because its UA also contains "Android"
      if (/windows phone/i.test(userAgent)) {
          return iosStoreURL;
      }

      if (/android/i.test(userAgent)) {
        return androidStoreURL;
      }

      // iOS detection from: http://stackoverflow.com/a/9039885/177710
      if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return iosStoreURL;
      }

      return iosStoreURL;
  }

  window.onload = function() {
    var appStoreURL = getMobileOperatingSystem();
    console.log("appStoreURL", appStoreURL);
    document.getElementById("gotoAppStore").href = appStoreURL;
};
