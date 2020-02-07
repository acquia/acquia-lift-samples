/**
 * @file lift-block.js
 */
(function (Drupal) {

  "use strict";

 

  /**
   * Changes the visibility of a lift block based on segments.
   */
  Drupal.behaviors.liftBlocks = {
    attach: function (context) {

      // Wait until Lift has finished finding out whats going on
      window.addEventListener('acquiaLiftStageCollection', function (e) {
        let liftSegmentsExist = typeof AcquiaLift.currentSegments === 'object';
        if (liftSegmentsExist) {
          // Loop through each current segment and look for blocks to show
          Object.values(AcquiaLift.currentSegments).forEach(value => {
            let liftclass = 'liftblock-' + value.id;
            let blocks = document.getElementsByClassName(liftclass);
            // Find every block that is marked for this segment, and show it
            Object.keys(blocks).forEach(key => {
              blocks[key].style.display = "inherit";
            });
          });
        }
      }); // End eventListener

    }
  };

}(Drupal));
