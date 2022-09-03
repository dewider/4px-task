class RatingStars {
    constructor(args){
        if(typeof args !== 'object') return;

        this.params = args;
        this.items = document.querySelectorAll(this.params.starsSelector);
        this.selectedItem = 0;
        this.input = document.querySelector(this.params.inputSelector);

        if(!this.items.length) return;
        this.items.forEach((item) => {
            item.addEventListener('mouseenter', this.itemHoverListener);
            item.addEventListener('mouseleave', this.itemHoverListener);
            item.addEventListener('click', this.itemClickListener);
        });
    }

    updateSelected(){
        for( let i = this.selectedItem; i < this.items.length; i++){
            this.items[i].classList.remove(this.params.starSelectClass);
        }
    }

    itemHoverListener = (event) => {
        if(event.type === 'mouseenter'){
            event.currentTarget.classList.add(this.params.starSelectClass);
            for (const item of this.items.entries()) {
                if(item[1] === event.currentTarget) break;
                item[1].classList.add(this.params.starSelectClass);
            }
        } else if(event.type === 'mouseleave'){
            this.updateSelected();
        }
    }

    itemClickListener = (event) => {
        event.preventDefault();
        for (const item of this.items.entries()) {
            if(item[1] === event.currentTarget){
                if(item[0] + 1 === this.selectedItem){
                    this.selectedItem = 0;
                } else {
                    this.selectedItem = item[0] + 1;
                    this.updateSelected();
                    break;
                }
            }
        }
        this.input.value = this.selectedItem;
    }
}

class AddCommentForm {
    constructor(args){
        if(typeof args !== 'object') return;

        this.params = args;

        this.form = document.querySelector(this.params.formSelector);
        this.componentName = this.form.dataset.componentName;
        this.signedParameters = this.form.dataset.componentSigned;
        if(this.params.statusSelector)
            this.status = this.form.querySelector(this.params.statusSelector);

        this.form.addEventListener('submit', this.submitHandler);

        this.sendedEvent = new CustomEvent('commentSended');
    }

    printMessage(msg){
        if(typeof this.status === 'object'){
            this.status.innerText = msg;
        }
    }

    submitHandler = (e) => {
        e.preventDefault(e);
        let data = {};
        this.form.querySelectorAll('input, textarea').forEach( (item, key) => {
            if(item.type === 'submit') return;
            let index = (item.name) ? item.name : key;
            data[index] = item.value;
        });
        BX.ajax.runComponentAction(this.componentName, 'add', {
            mode:'ajax',
            signedParameters: this.signedParameters,
            data: data
        }).then((response) => {
            this.printMessage(response.data.message);
            document.dispatchEvent(this.sendedEvent);
        }).catch(error => {
            this.printMessage(error.errors[0].message);
        });
    }
}

document.addEventListener("DOMContentLoaded", function(){

    let ratingStars = new RatingStars({
        starsSelector: 'button.rating-stars__item',
        starSelectClass: 'rating-stars__item_selected',
        inputSelector: '.rating-stars__input'
    });

    let commentForm = new AddCommentForm({
        formSelector: 'form.comment-add',
        statusSelector: '.comment-add__status'
    });

});