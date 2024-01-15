<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Munoz Bakery</title>
    <link rel="stylesheet" href="{{  asset('assets/css/bagger.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('/assets/photos/bakery-shop.png')}}" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<!-- Add these links to your HTML file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

</head>
<body>
    @include('employee.baggernav')

<div class="container-bakery">
    <div class="container-pad">
        <div class="container-text">
            <h4>Sale / Products</h4>
        </div>

      <?php $i = 0 ?>

      <div class="owl-carousel owl-theme item-container">
          @foreach ($products as $product)
          <div class="item slide" data-slide-index="{{ $i }}">
              <input type="hidden" value="{{ $product->id }}" id="product{{ $product->id }}">
              <div>
                  <img src="{{asset('Image/'. $product->image .'')}}" alt="image">
                  <h2>{{ $product->product_name }}</h2>
              </div>
              <h4><span>Price:</span> ₱{{ $product->price }}</h4>
              <h4><span>Quantity:</span> {{ $product->qty }}</h4>
              <h4><span>Total Price:</span> ₱{{ $product->price }}</h4>
              <div>
                  <h4>qty:</h4>
                  <input type="number" class="qty-input" value="0">
              </div>
          </div>
          <?php $i++ ?>
          @endforeach
      </div>

      <div class="calculate">
          <button id="calculateBtn">Compute</button>
      </div>

      <div class="product-table">
          <form class="table-container" id="calculateForm" method="post" action="{{ route('addsales') }}">
              @csrf
              <table class="calculate-table">
                  <thead>
                      <tr>
                          <th></th>
                          <th>Product</th>
                          <th>Price</th>
                          <th>Quantity</th>
                          <th>Total Price</th>
                          <th></th>
                      </tr>
                  </thead>
                  <tbody id="tableBody">
                      <!-- Table rows will be dynamically added here -->
                  </tbody>
                  <tfoot>
                      <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td><input id="money" type="hidden"></td>
                          <td id="totalPrice">0</td>
                          <td><input id="submitsale" type="submit" value="Submit" ></td>
                      </tr>
                  </tfoot>
              </table>
          </form>
      </div>



      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                ...
              </div>

            </div>
          </div>
        </div>

    </div>
</div>


    <script>
         document.getElementById('calculateBtn').addEventListener('click', function() {
            // Scroll to the product-table section
            document.querySelector('.product-table').scrollIntoView({ behavior: 'smooth' });
        });
 $(document).ready(function () {
    // Input event for quantity input fields
    $('.qty-input').on('input', function () {
        var enteredQty = parseInt($(this).val());
        var availableQty = parseInt($(this).closest('.item').find('h4:contains("Quantity")').text().replace('Quantity: ', ''));

        // Check if entered quantity is greater than available quantity
        if (enteredQty > availableQty || enteredQty < 0) {
            $(this).val(availableQty); // Set the input value to the available quantity



        }
    });

    $('#calculateBtn').on('click', function () {
        // Clear existing rows in the table
        $('#tableBody').empty();

        // Iterate over each item and add a row to the table only if qty is greater than zero
        $('.item').each(function () {
            var productId = $(this).find('input[type="hidden"]').val();
            var productName = $(this).find('h2').text();
            var priceText = $(this).find('h4:contains("Price")').text();
            var price = parseFloat(priceText.replace('Price: ₱', '').replace(',', ''));
            var enteredQty = parseInt($(this).find('.qty-input').val());
            var availableQty = parseInt($(this).find('h4:contains("Quantity")').text().replace('Quantity: ', ''));

            // Use Math.min to ensure entered quantity does not exceed available quantity
            var qty = Math.min(enteredQty, availableQty);

            // Add a row only if qty is greater than zero
            if (qty > 0 ) {
                var totalPrice = (price * qty).toFixed(2);
                $('#money').attr('type', 'number');
                $('#money').attr('placeholder', 'payment');
                // $('#money').attr('value', '0');


                // Append a new row to the table body
                $('#tableBody').append('<tr>' +
                    '<td><input type="hidden" name="productId[]" value="' + productId + '"></td>' +
                    '<td>' + productName + '</td>' +
                    '<td>' + price + '</td>' +
                    '<td><input type="hidden" name="total_qty[]" value="' + qty + '">' + qty + '</td>' +
                    '<td><input type="hidden" name="total_price[]" value="' + totalPrice + '">' + totalPrice + '</td>' +
                    '<td><button type="button" class="btn btn-danger">Delete</button></td>' +
                    '</tr>');
            }
        });
        $('.btn-danger').on('click', function () {
            var productId = $(this).closest('tr').find('input[name="productId[]"]').val();
            $('#product' + productId).closest('.item').find('.qty-input').val(0);
            $(this).closest('tr').remove();
            // Update the total price after deleting a row
            updateTotalPrice();
        });
           // Add a delete button to each row and handle deletion


        // Update the total price
        updateTotalPrice();
    });

    $('#calculateForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    var total = parseInt($('#totalPrice').text());
    var money = parseInt($('#money').val() ? parseInt($('#money').val()) : 0);

    // Check if money is less than the total price
    if (money < total || money == null || total == 0) {
        alert('Money should be equal or greater than the total price.');

    } else {
        $('#submitsale').attr('data-bs-toggle', "modal");
        $('#submitsale').attr('data-bs-target', "#exampleModal");
        $.ajax({
    type: "POST",
    url: "{{ route('addsales') }}",
    data: $('#calculateForm').serialize(),
    success: function (response) {
        // Display product details in the modal
        var salesData = response.salesData;

        // Clear the modal body content
        $('#exampleModal .modal-body').html('');

        // Check if there are sales data to display
        if (salesData && salesData.length > 0) {
            var totalPriceSum = 0;
            for (var i = 0; i < salesData.length; i++) {
                $('#exampleModal .modal-body').append('<p>' + salesData[i].quantity +
        ',  ' + salesData[i].productName +
        ',    ₱' + salesData[i].totalPrice + '</p>');
                    totalPriceSum += parseFloat(salesData[i].totalPrice);
            }

            $('#exampleModal .modal-body').append('<p>Total Price: ₱' + totalPriceSum.toFixed(2) + '</p>');

// Get the money received from the input
var moneyReceived = parseFloat($('#money').val());

// Calculate and display the change
var change = moneyReceived - totalPriceSum;
$('#exampleModal .modal-body').append('<p>Money Received: ₱' + moneyReceived.toFixed(2) + '</p>');
$('#exampleModal .modal-body').append('<p>Change: ₱' + change.toFixed(2) + '</p>');
        } else {
            // If there is no sales data, display a message
            $('#exampleModal .modal-body').html('<p>No sales data available.</p>');
        }

        $('#exampleModal').on('hidden.bs.modal', function () {
            // Refresh the page when the modal is closed
            location.reload();
        });
        // Show the modal after updating the content
        $('#exampleModal').modal('show').on("shown.bs.modal", function () {
    // window.setTimeout(function () {
    //     $("#exampleModal").modal("hide");
    // }, 8000);
});
    },
    error: function (error) {
        console.log('AJAX Error:', error);
    }
});
    }
});


    // Function to update the total price
    function updateTotalPrice() {
        var total = 0;
        $('.calculate-table tbody tr').each(function () {
            var totalPrice = parseFloat($(this).find('td:nth-child(5)').text());
            total += totalPrice;
        });

        $('#totalPrice').text(total.toFixed(2));
    }

    var el = $('.owl-carousel');

  var carousel;
  var carouselOptions = {
    margin: 1,
    nav: true,
    dots: true,
    slideBy: 'page',
    responsive: {
      0: {
        items: 2,
        rows: 2 //custom option not used by Owl Carousel, but used by the algorithm below
      },
      678: {
        items: 3,
        rows: 2 //custom option not used by Owl Carousel, but used by the algorithm below
      },
      960: {
        items: 4,
        rows: 2 //custom option not used by Owl Carousel, but used by the algorithm below
      },
      1165: {
        items: 5,
        rows: 2 //custom option not used by Owl Carousel, but used by the algorithm below
      },
      1335: {
        items: 6,
        rows: 2 //custom option not used by Owl Carousel, but used by the algorithm below
      }
    }
  };

  //Taken from Owl Carousel so we calculate width the same way
  var viewport = function() {
    var width;
    if (carouselOptions.responsiveBaseElement && carouselOptions.responsiveBaseElement !== window) {
      width = $(carouselOptions.responsiveBaseElement).width();
    } else if (window.innerWidth) {
      width = window.innerWidth;
    } else if (document.documentElement && document.documentElement.clientWidth) {
      width = document.documentElement.clientWidth;
    } else {
      console.warn('Can not detect viewport width.');
    }
    return width;
  };

  var severalRows = false;
  var orderedBreakpoints = [];
  for (var breakpoint in carouselOptions.responsive) {
    if (carouselOptions.responsive[breakpoint].rows > 1) {
      severalRows = true;
    }
    orderedBreakpoints.push(parseInt(breakpoint));
  }

  //Custom logic is active if carousel is set up to have more than one row for some given window width
  if (severalRows) {
    orderedBreakpoints.sort(function (a, b) {
      return b - a;
    });
    var slides = el.find('[data-slide-index]');
    var slidesNb = slides.length;
    if (slidesNb > 0) {
      var rowsNb;
      var previousRowsNb = undefined;
      var colsNb;
      var previousColsNb = undefined;

      //Calculates number of rows and cols based on current window width
      var updateRowsColsNb = function () {
        var width =  viewport();
        for (var i = 0; i < orderedBreakpoints.length; i++) {
          var breakpoint = orderedBreakpoints[i];
          if (width >= breakpoint || i == (orderedBreakpoints.length - 1)) {
            var breakpointSettings = carouselOptions.responsive['' + breakpoint];
            rowsNb = breakpointSettings.rows;
            colsNb = breakpointSettings.items;
            break;
          }
        }
      };

      var updateCarousel = function () {
        updateRowsColsNb();

        //Carousel is recalculated if and only if a change in number of columns/rows is requested
        if (rowsNb != previousRowsNb || colsNb != previousColsNb) {
          var reInit = false;
          if (carousel) {
            //Destroy existing carousel if any, and set html markup back to its initial state
            carousel.trigger('destroy.owl.carousel');
            carousel = undefined;
            slides = el.find('[data-slide-index]').detach().appendTo(el);
            el.find('.fake-col-wrapper').remove();
            reInit = true;
          }


          //This is the only real 'smart' part of the algorithm

          //First calculate the number of needed columns for the whole carousel
          var perPage = rowsNb * colsNb;
          var pageIndex = Math.floor(slidesNb / perPage);
          var fakeColsNb = pageIndex * colsNb + (slidesNb >= (pageIndex * perPage + colsNb) ? colsNb : (slidesNb % colsNb));

          //Then populate with needed html markup
          var count = 0;
          for (var i = 0; i < fakeColsNb; i++) {
            //For each column, create a new wrapper div
            var fakeCol = $('<div class="fake-col-wrapper"></div>').appendTo(el);
            for (var j = 0; j < rowsNb; j++) {
              //For each row in said column, calculate which slide should be present
              var index = Math.floor(count / perPage) * perPage + (i % colsNb) + j * colsNb;
              if (index < slidesNb) {
                //If said slide exists, move it under wrapper div
                slides.filter('[data-slide-index=' + index + ']').detach().appendTo(fakeCol);
              }
              count++;
            }
          }
          //end of 'smart' part

          previousRowsNb = rowsNb;
          previousColsNb = colsNb;

          if (reInit) {
            //re-init carousel with new markup
            carousel = el.owlCarousel(carouselOptions);
          }
        }
      };

      //Trigger possible update when window size changes
      $(window).on('resize', updateCarousel);

      //We need to execute the algorithm once before first init in any case
      updateCarousel();
    }
  }

  //init
  carousel = el.owlCarousel(carouselOptions);
});

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
