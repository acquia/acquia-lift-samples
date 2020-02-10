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
          // Loop through each current segment
          Object.values(AcquiaLift.currentSegments).forEach(value => {

            // Look for blocks to show
            let liftClass = 'liftblock-' + value.id;
            let liftClassBlocks = document.getElementsByClassName(liftClass);
            // Find every block that is marked for this segment, and show it
            Object.keys(liftClassBlocks).forEach(key => {
              liftClassBlocks[key].style.display = "inherit";
            });

            // Look for blocks to hide
            let liftClassNegate = 'liftblock-' + value.id + '-not';
            let liftClassNegateBlocks = document.getElementsByClassName(liftClassNegate);
            // Find every block that is marked for this segment, and hide it
            Object.keys(liftClassNegateBlocks).forEach(key => {
              liftClassNegateBlocks[key].style.display = "none";
            });
          });
        }
      }); // End eventListener

    }
  };

}(Drupal));
