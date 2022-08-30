class CommentsListComponent {
    constructor(args) {
        if (typeof args !== 'object') return;

        this.params = args;
        this.commentsWrap = document.querySelector(this.params.commentsSelector);
        this.componentName = this.commentsWrap.dataset.componentName;
        this.signedParameters = this.commentsWrap.dataset.signedParams;
        this.sort = this.commentsWrap.dataset.sort;
        this.perPage = this.commentsWrap.dataset.perPage;
        this.page = 1;
        this.attachButtonsHandler();
        if(this.commentsWrap.dataset.userAuth == 'true'){
            document.addEventListener('commentSended', event => {
                this.refresh(true);
            });
        }
    }
    refresh(setFirst = false){
        if(setFirst) this.page = 1;
        BX.ajax.runComponentAction(this.componentName, 'get', {
            mode:'class',
            signedParameters: this.signedParameters,
            data: {
                sort: this.sort,
                perPage: this.perPage,
                page: this.page
            }
        }).then((response) => {
            let tmpEl = document.createElement('DIV');
            tmpEl.innerHTML = response.data;
            let currentUri = new URL(location.href);
            tmpEl.querySelectorAll(this.params.buttonsSelector).forEach(item => {
                let uri = new URL(item.href);
                let page = uri.searchParams.get(this.params.pagerId);
                if(page)
                    currentUri.searchParams.set(this.params.pagerId, page);
                else 
                    currentUri.searchParams.delete(this.params.pagerId);
                item.href = currentUri.toString();
            });
            this.commentsWrap.innerHTML = tmpEl.querySelector(this.params.commentsSelector).innerHTML;
            this.attachButtonsHandler();
        }).catch(error => {
            console.log(error);
        });
    }
    attachButtonsHandler() {
        this.buttons = document.querySelectorAll(this.params.buttonsSelector);
        this.buttons.forEach(item => {
            item.removeEventListener('click', this.buttonsClickHandler)
            item.addEventListener('click', this.buttonsClickHandler)
        });
    }
    buttonsClickHandler = (event) => {
        event.preventDefault();
        let pageNumStr = event.currentTarget.href.match(/=page-\d+$/);
        this.page = (pageNumStr) ? pageNumStr[0].substr(6) : 1;
        this.refresh();
    }
}

document.addEventListener("DOMContentLoaded", function () {
    commentsList = new CommentsListComponent({
        commentsSelector: '.comments-wrap',
        buttonsSelector: '.bx-pagination-container > ul > li > a',
        pagerId: 'nav-comments'
    });
});