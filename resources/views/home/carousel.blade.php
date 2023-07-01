<div id="multiItemCarousel" class="carousel slide container mt-4" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <div class="col-sm-4">
        <img src="https://seudamgo.com/wp-content/uploads/2023/04/1-1.jpg" class="d-block w-100" alt="Slide 1">
      </div>
    </div>
    <div class="carousel-item">
      <div class="col-sm-4">
        <img src="https://seudamgo.com/wp-content/uploads/2023/04/2-1.jpg" class="d-block w-100" alt="Slide 2">
      </div>\
    </div>
    <div class="carousel-item">
      <div class="col-sm-4">
        <img src="https://seudamgo.com/wp-content/uploads/2023/04/3-1.jpg" class="d-block w-100" alt="Slide 3">
      </div>
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#multiItemCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#multiItemCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<script type="module">
  let items = document.querySelectorAll('.carousel .carousel-item')

  items.forEach((el) => {
    const minPerSlide = 3
    let next = el.nextElementSibling
    for (var i=1; i<minPerSlide; i++) {
      if (!next) {
          // wrap carousel by using first child
          next = items[0]
      }
      let cloneChild = next.cloneNode(true)
      el.appendChild(cloneChild.children[0])
      next = next.nextElementSibling
    }
  });
</script>
<style>
  @media (max-width: 767px) {
		.carousel-inner .carousel-item > div {
			display: none;
		}
		.carousel-inner .carousel-item > div:first-child {
			display: block;
		}
	}

	.carousel-inner .carousel-item.active,
	.carousel-inner .carousel-item-next,
	.carousel-inner .carousel-item-prev {
		display: flex;
	}

	/* medium and up screens */
	@media (min-width: 768px) {

		.carousel-inner .carousel-item-end.active,
		.carousel-inner .carousel-item-next {
			transform: translateX(33%);
		}

		.carousel-inner .carousel-item-start.active, 
		.carousel-inner .carousel-item-prev {
			transform: translateX(-33%);
		}
	}

	.carousel-inner .carousel-item-end,
	.carousel-inner .carousel-item-start { 
		transform: translateX(0);
	}
</style>