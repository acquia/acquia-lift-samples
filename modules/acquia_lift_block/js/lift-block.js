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
        let liftSegmentExist = typeof AcquiaLift.currentSegments === 'object';
        // Set our intitial lists
        let liftSegments = (liftSegmentExist) ? AcquiaLift.currentSegments : {};
        let liftBlocks = document.getElementsByClassName('liftblock');
        let liftBlocksNot = document.getElementsByClassName('liftblock-not');
        // Look for blocks to show
        Object.keys(liftBlocks).forEach(key => {
          if (classContainsSegment(liftBlocks[key], liftSegments)) {
            liftBlocks[key].style.display = "inherit";
          }
        });
        // Look for negated blocks to show
        Object.keys(liftBlocksNot).forEach(key => {
          if (!classContainsSegment(liftBlocksNot[key], liftSegments)) {
            liftBlocksNot[key].style.display = "inherit";
          }
        });
      }); // End eventListener

    }
  };

}(Drupal));

/**
 * Examine an element to see if it contains any of the segments in the list.
 * 
 * @param {element} item 
 * @param {object} segments 
 */
function classContainsSegment(item, segments) {
  let contains = false;
  Object.values(segments).forEach(value => {
    contains = (contains) ? contains : item.classList.contains('liftblock-segment-' + value.id);
  });
  return contains;
}
