<!-- Edit Comment Modal -->
<div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCommentForm" method="POST" action="update_comment.php">
                    <input type="hidden" id="editCommentId" name="id" value="">
                    <input type="hidden" id="f_name" name="f_name" value="">
                    <input type="hidden" id="fid" name="fid" value="">
                    <input type="hidden" id="uid" name="uid" value="">
                    
                    <div class="form-group">
                        <label for="editCommentText">Comment</label>
                        <textarea class="form-control" id="editCommentText" name="comment" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
