<div class="d-flex">
    <input type="number" min="0" max="{{auth()->user()->diamonds}}" class="form-control mr-2" style="min-width: 5rem; max-width:7rem">
    <button type="button" class="btn btn-primary send" user_id="{{$id}}">Send</button>
</div>
