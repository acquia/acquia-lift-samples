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
      let liftEnabled = typeof AcquiaLift === 'object';
      console.log("I should always see this!");
      if (liftEnabled) {
        let liftSegmentsExist = typeof AcquiaLift.currentSegments === 'object';
        if (liftSegmentsExist) {
          Object.values(AcquiaLift.currentSegments).forEach(value => {
            let liftclass = 'liftblock-' + value.id;
            let blocks = document.getElementsByClassName(liftclass);
            Object.keys(blocks).forEach(key=>{
               //blocks[key].style.display = "block";
               blocks[key].style.borderTop = "25px solid red";
               console.log(value.id);
            });
          });
        }
      }
    }
  };

}(Drupal));
