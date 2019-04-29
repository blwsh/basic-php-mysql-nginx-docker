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
    static searchRequestUrl = window.searchRequestUrl;

    static basketRequestUrl = window.basketRequestUrl;

    static basketAddRequestUrl = window.basketAddRequestUrl;

    static basketRemoveRequestUrl = window.basketRemoveRequestUrl;

    constructor(buttonElement, counterElement, containerElement) {
        // Set button element.
        if (typeof buttonElement === "string") {
            this.buttonElement = document.querySelector(buttonElement);
        } else if (typeof buttonElement === "object") {
            this.buttonElement = buttonElement;
        } else {
            throw new Error("Basket button element not specified.");
        }

        // Set counter element.
        if (typeof counterElement === "string") {
            this.counterElement = document.querySelector(counterElement);
        } else if (typeof counterElement === "object") {
            this.counterElement = counterElement;
        } else {
            throw new Error("Basket counter element not specified.");
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

    show() {
        this.containerElement.style.display = 'block';
    }

    hide() {
        this.containerElement.style.display = 'none';
    }

    toggle() {
        this.containerElement.style.display === 'none' ? this.show() : this.hide()
    }

    constructBasket() {
        this.containerElement.classList.add('basket');
        this.containerElement.style.display = 'none';
        this.populateBasket();
    }

    populateBasket() {
        const basketRequest = new XMLHttpRequest();

        basketRequest.open("GET", Basket.basketRequestUrl);
        basketRequest.send();

        basketRequest.onreadystatechange = (e) => {
            if (basketRequest.readyState === 4 && basketRequest.status === 200) {
                this.resetBasket();

                if (basketRequest.responseText) {
                    const basketData = JSON.parse(basketRequest.responseText);

                    if (basketData.count) {
                        this.counterElement.innerText = basketData.count > 0 ? basketData.count : null;

                        if (basketData.items) {
                            basketData.items.forEach(item => {
                                this.addItem(item);
                            });
                        }

                        this.addCheckoutButton();
                    } else {
                        this.containerElement.innerText = 'Your basket is currently empty.'
                    }
                }
            }
        }
    }

    resetBasket() {
        this.containerElement.innerHTML = null;
    }

    addItem(data) {
        // Create basket item row

        const el = document.createElement('div');
        el.classList.add('basket__item');

        // Name

        const nameEl = document.createElement('div');
        nameEl.innerText = data.item.filmtitle;
        nameEl.classList.add('basket__item__name');
        el.append(nameEl);

        // Quantity

        const quantityEl = document.createElement('div');
        quantityEl.innerText = data.quantity;
        quantityEl.classList.add('basket__item__quantity');
        el.append(quantityEl);

        // Basket row controls

        const controlsEl = document.createElement('div');
        controlsEl.classList.add('basket__item__controls');

        // Add buttons

        const buttons = [
            { 'label': '-', 'action': Basket.basketRemoveRequestUrl },
            { 'label': '+', 'action': Basket.basketAddRequestUrl }
        ];

        buttons.forEach(button => {

            // Hidden input

            const inputEl = document.createElement('input');
            inputEl.value = data.item.filmid;
            inputEl.name = 'filmid';
            inputEl.type = 'hidden';

            // Add form & button

            const formEl = document.createElement('form');
            formEl.action =  button.action;
            formEl.method = 'post';

            const buttonEl = document.createElement('button');
            buttonEl.innerText = button.label;
            buttonEl.type = 'submit';

            buttonEl.onclick = (e) => {
                e.preventDefault();
                this.updateRequest(button.action, data.item.filmid);
            };

            formEl.append(inputEl);
            formEl.append(buttonEl);
            controlsEl.append(formEl);
        }) ;


        // Add buttons to form controls container

        el.append(controlsEl);

        // Add row to container

        this.containerElement.append(el);
    }

    addCheckoutButton() {
        const checkoutButton = document.createElement('a');
        checkoutButton.innerText = 'Go to checkout';
        checkoutButton.href = window.checkoutUrl;
        checkoutButton.className = 'btn btn--sm btn--block mt';

        this.containerElement.append(checkoutButton);
    }

    updateRequest(action, id) {
        const basketRequest = new XMLHttpRequest();

        basketRequest.open("POST", action);
        basketRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        basketRequest.send('filmid=' + id);

        basketRequest.onreadystatechange = () => {
            if (basketRequest.readyState === 4 && basketRequest.status === 200) {
                this.populateBasket();
            }
        }
    }

    bindEvents() {
        this.buttonElement.onclick = () => this.toggle();
    }
}

class Search {
    constructor() {
        this.el = document.querySelector('.site-search');

        this.input = this.el.querySelector('input[type="text"]');

        this.stage = this.el.querySelector('.site-search__results');

        this.input.addEventListener('input', this.typeHandler);
        this.input.addEventListener('propertychange', this.typeHandler);
        this.input.addEventListener('focusout', e => {
            setTimeout(() => {
                this.stage.innerHTML = null
            }, 600);
        });
    }

    typeHandler = e => {
        if (this.timeout) {
            clearTimeout(this.timeout);
        }

        this.timeout = setTimeout(() => {
            this.query = e.target.value;

            if (e.target.value.length > 3) {
                const searchRequest = new XMLHttpRequest();

                searchRequest.open("GET", Basket.searchRequestUrl + "?q=" + encodeURIComponent(e.target.value));
                searchRequest.send();

                searchRequest.onreadystatechange = (e) => {
                    if (searchRequest.readyState === 4 && searchRequest.status === 200) {
                        if (searchRequest.responseText) {
                            this.stage.innerHTML = null;

                            const searchData = JSON.parse(searchRequest.responseText);

                            if (searchData ) {
                                searchData.forEach(data => {
                                    const regex = new RegExp(this.query, 'gi');

                                    const result = document.createElement('a');
                                    result.innerHTML = data.filmtitle.replace(regex, '<strong>' + `${this.query}` + '</strong>');
                                    result.href = window.baseUrl + 'films/' + data.filmid;

                                    this.stage.append(result);
                                })
                            } else {
                                this.stage.innerHTML = 'No results found.';
                            }
                        }
                    }
                }
            } else {
                this.stage.innerHTML = null;
            }
        }, 200);
    }
}

/**
 * App code.
 */

const search = new Search();

const basket = new Basket('#basket-button', '#basket-counter', '#basket-container');

const basketBtns = document.querySelectorAll('[data-basket-add]');

if (basketBtns) {
    basketBtns.forEach(btn => {
        btn.onclick = e => {
            console.log(btn.getAttribute('data-basket-add'));
            e.preventDefault();

            basket.updateRequest(
                Basket.basketAddRequestUrl,
                btn.getAttribute('data-basket-add')
            );

            basket.show();
        }
    })
}

if (document.querySelector('#home-slider')) {
    new Slider('#home-slider');
}

