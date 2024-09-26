$(document).ready(function () {
  if (document.getElementById('date-facet-slider')) {
    let slider = document.getElementById('date-facet-slider');
    let totalMin = $(slider).data('totalmin');
    let totalMax = $(slider).data('totalmax');
    let currentMin = $(slider).data('currentmin');
    let currentMax = $(slider).data('currentmax');
    let disabled = $(slider).data('disabled');
    let minInput = document.getElementById('dateRangeMin');
    let maxInput = document.getElementById('dateRangeMax');
    let options = {
      start: [currentMin, currentMax],
      step: 1,
      format: {
        to: function (value) {
          return Math.round(value);
        },
        from: function (value) {
          return value;
        }
      },
      tooltips: false,
      handleAttributes: [
        { 'aria-label': 'Date Min' },
        { 'aria-label': 'Date Max' },
      ],
      range: {
        'min': totalMin,
        'max': totalMax
      }
    };
    if ((totalMin != 0) && (totalMax != 0)) {
      options.tooltips = true;
    }
    noUiSlider.create(slider, options);
    if (disabled == "disabled") {
      slider.noUiSlider.disable();
    }
    mergeTooltips(slider, 15, ' - ');
    slider.noUiSlider.on('update', function (values, handle) {
      let value = values[handle];

      if (handle) {
        maxInput.value = Math.round(value);
      } else {
        minInput.value = Math.round(value);
      }
    });

    $('#dateRangeFacet').submit(function (event) {
      event.preventDefault();
      let url = new URL($(this).attr('action'));
      url.searchParams.append($(minInput).attr('name'), $(minInput).val());
      url.searchParams.append($(maxInput).attr('name'), $(maxInput).val());
      window.location.href = url;
    });
  }
});

function mergeTooltips(slider, threshold, separator) {

  var textIsRtl = getComputedStyle(slider).direction === 'rtl';
  var isRtl = slider.noUiSlider.options.direction === 'rtl';
  var isVertical = slider.noUiSlider.options.orientation === 'vertical';
  var tooltips = slider.noUiSlider.getTooltips();
  var origins = slider.noUiSlider.getOrigins();

  // Move tooltips into the origin element. The default stylesheet handles this.
  tooltips.forEach(function (tooltip, index) {
    if (tooltip) {
      origins[index].appendChild(tooltip);
    }
  });

  slider.noUiSlider.on('update', function (values, handle, unencoded, tap, positions) {

    var pools = [[]];
    var poolPositions = [[]];
    var poolValues = [[]];
    var atPool = 0;

    // Assign the first tooltip to the first pool, if the tooltip is configured
    if (tooltips[0]) {
      pools[0][0] = 0;
      poolPositions[0][0] = positions[0];
      poolValues[0][0] = values[0];
    }

    for (var i = 1; i < positions.length; i++) {
      if (!tooltips[i] || (positions[i] - positions[i - 1]) > threshold) {
        atPool++;
        pools[atPool] = [];
        poolValues[atPool] = [];
        poolPositions[atPool] = [];
      }

      if (tooltips[i]) {
        pools[atPool].push(i);
        poolValues[atPool].push(values[i]);
        poolPositions[atPool].push(positions[i]);
      }
    }

    pools.forEach(function (pool, poolIndex) {
      var handlesInPool = pool.length;

      for (var j = 0; j < handlesInPool; j++) {
        var handleNumber = pool[j];

        if (j === handlesInPool - 1) {
          var offset = 0;

          poolPositions[poolIndex].forEach(function (value) {
            offset += 1000 - value;
          });

          var direction = isVertical ? 'bottom' : 'right';
          var last = isRtl ? 0 : handlesInPool - 1;
          var lastOffset = 1000 - poolPositions[poolIndex][last];
          offset = (textIsRtl && !isVertical ? 100 : 0) + (offset / handlesInPool) - lastOffset;

          // Center this tooltip over the affected handles
          tooltips[handleNumber].innerHTML = poolValues[poolIndex].join(separator);
          tooltips[handleNumber].style.display = 'block';
          tooltips[handleNumber].style[direction] = offset + '%';
        } else {
          // Hide this tooltip
          tooltips[handleNumber].style.display = 'none';
        }
      }
    });
  });
}