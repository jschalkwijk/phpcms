/**
 * Created by jorn on 11-11-17.
 */

var model = {
    replyBox : function(comment){

        var textarea = document.createElement('textarea');
        textarea.name = 'content';
        textarea.className = 'form-control';
        textarea.rows = 5;

        var commentID = document.createElement('input');
        commentID.value = this.commentID(comment);
        commentID.type = 'hidden';
        commentID.name = 'comment_id';

        var submit = document.createElement('button');
        submit.type = 'submit';
        submit.name = 'submitReply';
        submit.innerHTML = 'Add Reply';
        submit.className = 'btn btn-info btn-circle text-uppercase';

        var postID = window.location.pathname.match(/[0-9]+/)[0];

        var form = document.createElement('form');
        form.appendChild(textarea);
        form.appendChild(commentID);
        form.appendChild(submit);
        form.action = '/admin/replies';
        form.method = 'post';

        comment.appendChild(form);
    },
    commentID : function (comment) {
        var classes = comment.classList;
        var regX = /^comment-[0-9]+$/;
        for (var i = 0; i < classes.length; i++){
            if(classes[i].match(regX)){
                var commentID = classes[i].split('comment-').pop();
                console.log(commentID);
            }
        }
        return commentID;
    }
};

var controller = {
    actions : function(){
        var replies = document.getElementsByClassName('reply');

        for(var i = 0; i < replies.length; i++) {
            (function(i){
                var reply = replies[i];
                var comment = reply.parentElement;
                var toggle = false;

                reply.onclick = function () {
                    if (!toggle){
                        model.replyBox(comment);
                        toggle = true;
                    } else {
                        comment.getElementsByTagName('form')[0].remove();
                        // reply.onclick = null;
                        toggle = false;
                    }
                };
            })(i);
        }
    }
};


function init2(){
    controller.actions();
}

addLoadEvent(init2());