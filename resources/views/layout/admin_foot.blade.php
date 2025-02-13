{{-- 확인 모달 --}}
<div id="system_alert" hidden>
    <div class="modal modal-sheet position-fixed d-block top-50 start-50 translate-middle" tabindex="-1" role="dialog"
        style="width:20%;height:auto">
        <div class="modal-dialog m-0" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5 msg_title"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="this.closest('#system_alert').hidden = true;"></button>
                </div>
                <div class="modal-body py-0">
                    <p class="msg_content"></p>
                </div>
                <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                    <button type="button" class="msg_btn1 btn btn-lg btn-primary"></button>
                    <button type="button" class="msg_btn2 btn btn-lg btn-secondary" hidden></button>
                </div>
            </div>
        </div>
    </div>
</div>
<script></script>
</body>

</html>
