/**
 * Slider class.
 *
 * @method next - Goes to the next slide or loops back if current slide is last
 *                in slider.
 *
 * @method prev - Goes to the previous slide or loops if current slide is first
 *                in slider.
 *
 * @var el string|object The query selector for an element or the actual element
 *                       to be instantiated as a slider.
 */
class Slider {
    constructor(el) {
        if (typeof el === "string") {
            this.el = document.querySelector(el);
        } else if (typeof el === "object") {
            this.el = el;
        } else {
            throw new Error("Slider element not specified.");
        }

        this.currentSlide = 1;
        this.slides = this.el.querySelectorAll('.slide');

        // Add slider class to container if does not exist already.
        if (!this.el.classList.contains('slider')) {
            this.el.classList.add('slider');
        }

        this.constructStage();
        this.constructDotsStage();
        this.stageSlides();
        this.bindEvents();
        this.startAutoSlide();
    }

    constructStage() {
        // Create the stage.
        this.stage = document.createElement('div');
        this.stage.classList.add('slider__stage');

        // Move the elements.
        this.el.prepend(this.stage);

        this.setStageWidth();
        this.setSlidesWidth();
    }

    setStageWidth() {
        this.stage.style.width = (this.slides.length * this.el.clientWidth) + 'px';
    }

    setSlidesWidth() {
        this.slides.forEach(el => {
            el.style.width = this.el.clientWidth + 'px';
            el.style.cssFloat = 'left';
        });
    }

    constructDotsStage() {
        this.dotsStage = document.createElement('div');
        this.dotsStage.classList.add('slider__dots');

        let count = 1;
        this.slides.forEach(el => {
            let dot = document.createElement('span');
            dot.index = count;
            dot.classList.add('slider__dot');
            dot.onclick = () => this.goTo(dot.index);
            this.dotsStage.append(dot);

            count++;
        });

        this.el.append(this.dotsStage);

        this.dots = this.dotsStage.querySelectorAll('.slider__dot');
    }

    stageSlides() {
        this.slides.forEach(el => {
            this.stage.appendChild(el);
        })
    }

    bindEvents() {
        document.onkeydown = e => {
            if (e.code === "ArrowRight") {
                this.next()
            }
            else if (e.code === "ArrowLeft") {
                this.prev();
            }
        };

        window.onresize = () => this.resize();
    }

    resize() {
        if (this.resizeTimeout) {
            clearTimeout(this.resizeTimeout);
        }

        this.resizeTimeout = setTimeout(() => {
            this.setStageWidth();
            this.setSlidesWidth();
        }, 200);
    }

    startAutoSlide() {
        this.interval = setInterval(() => this.next(), 5000);
    }

    next() {
        this.goTo(this.currentSlide < this.slides.length ? ++this.currentSlide : this.currentSlide = 1);
    }

    prev() {
        this.goTo(this.currentSlide > 1 ? --this.currentSlide : this.currentSlide = this.slides.length);
    }

    goTo(slide) {
        if (this.timeout) {
            clearTimeout(this.timeout);
        }

        this.timeout = setTimeout(() => {
            // Update active class on slides
            this.slides.forEach(el => el.classList.remove('slide--active'));
            this.slides[slide - 1].classList.add('slide--active');

            // Update active class on dots.
            this.dots.forEach(el => el.classList.remove('slider__dot--active'));
            this.dots[slide - 1].classList.add('slider__dot--active');

            // Transform the stage to show slide.
            this.stage.style.transform = 'translateX(' + (slide - 1) * -this.el.clientWidth + 'px)';
            clearInterval(this.interval); this.startAutoSlide();
        }, 350);
    }
}

class Basket {
    static basketRequestUrl = '/basket/get';

    constructor(buttonElement, containerElement) {
        // Set button element.
        if (typeof buttonElement === "string") {
            this.buttonElement = document.querySelector(buttonElement);
        } else if (typeof buttonElement === "object") {
            this.buttonElement = buttonElement;
        } else {
            throw new Error("Basket button element not specified.");
        }

        // Set container element
        if (typeof containerElement === "string") {
            this.containerElement = document.querySelector(containerElement);
        } else if (typeof containerElement === "object") {
            this.containerElement = containerElement;
        } else {
            throw new Error("Basket container element not specified.");
        }

        this.constructBasket();
        this.bindEvents();
    }

    constructBasket() {
        this.containerElement.classList.add('basket');
        this.containerElement.style.display = 'none'
        this.populateBasket();
    }

    populateBasket() {
        fetch(Basket.basketRequestUrl)
            .then(data => {
                console.log(data);
            });
    }

    bindEvents() {
        this.buttonElement.onclick = () => {
            this.containerElement.style.display =
                this.containerElement.style.display === 'none' ?
                    'block' :
                    'none';
        }
    }
}

/**
 * App code.
 */

new Basket('#basket-button', '#basket-container');

if (document.querySelector('#home-slider')) {
    new Slider('#home-slider');
}

