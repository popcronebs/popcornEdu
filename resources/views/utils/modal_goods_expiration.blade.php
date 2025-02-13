@include('layout.parent_head')
<style>
#modal_goods_expiration label span{
    border-radius: 50%;
}
</style>
{{-- 모달 /  이용권 만료 결제 진행 --}}
<div class="modal fade " id="modal_goods_expiration" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog" >
    <div class="modal-dialog rounded" >
        <div class="modal-content border-none rounded-4 modal-shadow-style" style="width:528px">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5 text-b-24px" id="">
                </h1>
                <button type="button" style="width:32px;height:32px"
                    class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-sb-32px text-center">자녀분의 이용권이 만료됩니다.</div>
                <div class="text-b-32px text-center mt-2">결제를 진행해주세요!</div>

                <div class="py-4"></div>
                <div class="pt-1"></div>

                <div class="scale-bg-black" style="height:2px;"></div>
                <table class="table table-bordered text-sb-20px ">
                    <tr>
                        <th class="border-bottom p-4 scale-text-gray_05">이용권</th>
                        <td class="border border-end-0 p-4 text-black">BASIC 3개월</td>
                    </tr>
                    <tr>
                        <th class="border-bottom p-4 scale-text-gray_05">이용기간</th>
                        <td class="border border-end-0 p-4">23.07.01-23.09.30</td>
                    </tr>
                    <tr>
                        <th class="border-bottom p-4 scale-text-gray_05">결제금액</th>
                        <td class="border border-end-0 p-4 text-black">500,000원</td>
                    </tr>
                </table>

                <!-- 이용권 업그레이드 상담 -->
                <div class="modal-shadow-style rounded-2 d-flex h-center p-4 border-primary-y-hover">
                    <div class="col-auto h-center">
                        <img src="{{asset('images/ticket_icon.svg')}}" width="52">
                    </div>
                    <div class="col ms-3">
                        <div class="text-sb-20px">이용권 업그레이드 상담</div>
                        <div class="text-sb-20px scale-text-gray_05 mt-2">고가형 이용권 상담을 진행합니다.</div>
                    </div>
                </div>

                <!-- 재등록 결제하기 -->
                <div class="modal-shadow-style rounded-2 d-flex h-center p-4 mt-3 border-primary-y-hover">
                    <div class="col-auto h-center">
                        <img src="{{asset('images/yellow_card_icon.svg')}}" width="52">
                    </div>
                    <div class="col ms-3">
                        <div class="text-sb-20px">재등록 결제하기</div>
                        <div class="text-sb-20px scale-text-gray_05 mt-2">재등록을 위해 결제페이지로 이동합니다.</div>
                    </div>
                </div>

                <!-- 80 -->
                <div class="py-4"> </div>
                <div class="py-3"> </div>

            </div>
                <div class="scale-bg-gray_01 p-4 h-center rounded-bottom-4 gap-1 justify-content-end">
                    <label class="checkbox">
                        <input type="checkbox" class="" onchange="">
                        <span class="">
                        </span>
                    </label>
                    <span class="text-sb-20px scale-text-gray_05">7일간 보지 않기</span>
                </div>
        </div>
    </div>
</div>

<script>
const myModal = new bootstrap.Modal(document.getElementById('modal_goods_expiration'), {
    keyboard: false
});
myModal.show();
</script>

