/**
 * Determine the mobile operating system.
 * This function returns one of 'iOS', 'Android', 'Windows Phone', or 'unknown'.
 *
 * @returns {String}
 */
function getMobileOperatingSystem() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;

        // Windows Phone must come first because its UA also contains "Android"
      if (/windows phone/i.test(userAgent)) {
          return "Windows";
      }

      if (/android/i.test(userAgent)) {
        return "Android";
      }

      // iOS detection from: http://stackoverflow.com/a/9039885/177710
      if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "iPhone";
      }

      return "Unknown";
  }

  window.onload = function() {
    let stickerMakerANDROIDStoreURL = 'https://play.google.com/store/apps/details?id=com.braincraftapps.droid.stickermaker';
    let stickerMakerIOSStoreURL = 'https://apps.apple.com/us/app/id1505991796';
    let os = getMobileOperatingSystem();
    let stickerMakerStoreURL = stickerMakerIOSStoreURL;
    if(os === 'Android'){
        stickerMakerStoreURL = stickerMakerANDROIDStoreURL;
    }
    var links =  document.getElementsByClassName("gotoStickerMakerAppStore");
    for(let i = 0; i < links.length; i++){
        links[i].href = stickerMakerStoreURL;
    }
};
