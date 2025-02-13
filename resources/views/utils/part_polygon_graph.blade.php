{{-- 필요 상위에 선언 <script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script> --}}
<style>
  .chart-wrap {
    position: relative;
    width: max-content;
  }

  .chart-wrap .chart-text span {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    white-space: nowrap;
    color: #999999;
  }

  .chart-wrap.angles-6 .chart-text span.text-item-1 {
    top: calc(-5% - 0px);
    left: 70%;
  }

  .chart-wrap.angles-6 .chart-text span.text-item-2 {
    top: 50%;
    left: calc(110% + 0px);
  }

  .chart-wrap.angles-6 .chart-text span.text-item-3 {
    top: calc(105% + 0px);
    left: 80%;
  }

  .chart-wrap.angles-6 .chart-text span.text-item-4 {
    top: calc(105% + 0px);
    left: calc(20% + 0px);
  }

  .chart-wrap.angles-6 .chart-text span.text-item-5 {
    top: calc(50%);
    left: calc(-10% - 0px);
  }

  .chart-wrap.angles-6 .chart-text span.text-item-6 {
    top: calc(-5% - 0px);
    left: calc(20% + 0px);
  }

  .chart-wrap.angles-4 .chart-text span.text-item-1 {
    top: calc(-5% - 10px);
    left: 50%;
  }

  .chart-wrap.angles-4 .chart-text span.text-item-2 {
    top: 50%;
    left: calc(110% + 10px);
  }

  .chart-wrap.angles-4 .chart-text span.text-item-3 {
    top: calc(105% + 10px);
    left: 50%;
  }

  .chart-wrap.angles-4 .chart-text span.text-item-4 {
    top: calc(50%);
    left: calc(-10% - 10px);
  }

  .chart-wrap.angles-3 .chart-text span.text-item-1 {
    top: calc(-5% - 10px);
    left: 50%;
  }

  .chart-wrap.angles-3 .chart-text span.text-item-2 {
    top: calc(100% + 0px);
    left: 100%;
  }

  .chart-wrap.angles-3 .chart-text span.text-item-3 {
    top: calc(100% + 0px);
    left: calc(5% + 0px);

  }

  .legend .legend-item .legend-item-circle {
    width: 20px;
    height: 20px;
    border-radius: 50%;
  }

  .legend {
    gap: 24px;
    justify-content: center;
  }

  .legend .legend-item {
    display: flex;
    align-items: center;
    background-color: #fff;
    gap: 8px;
  }

  .legend-item-circle-1 {
    border: 4px solid #FF5065;
  }

  .legend-item-circle-2 {
    border: 4px solid #5057FF;
  }

  .legend-item-circle-3 {
    border: 4px solid #2FCD94;
  }

  .legend-item-circle-4 {
    border: 4px solid #E5E5E5;
  }
</style>

<!-- 육각형  -->
<div data-polygon="6" data-max-num="171"
    class="modal-shadow-style px-4 py-32 row justify-content-between rounded-3 h-100 flex-column" hidden>
    <div class="d-flex justify-content-center p-5">
        <div class="d-flex justify-content-center chart-wrap angles-6">
            <svg id="hexagon-chart6" width="320" height="320" viewBox="0 0 360 320" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <g transform="translate(10, 0)">
                    <path
                        d="M248.998 0H93.0024C87.6362 0 82.6827 2.8575 80.0034 7.5L2.00951 142.5C-0.669838 147.143 -0.669838 152.857 2.00951 157.5L80.0034 292.5C82.6827 297.142 87.6437 300 93.0024 300H248.998C254.364 300 259.317 297.142 261.997 292.5L339.99 157.5C342.67 152.857 342.67 147.143 339.99 142.5L261.997 7.5C259.317 2.8575 254.356 0 248.998 0Z"
                        fill="#F9F9F9" />
                    <path d="M240.331 30H101.673L32.3398 150L101.673 270H240.331L309.664 150L240.331 30Z"
                        fill="#D1FBEC" />
                    <path
                        d="M217.797 60H124.2C120.98 60 118.008 61.7175 116.402 64.5L69.6069 145.5C68.0008 148.283 68.0008 151.717 69.6069 154.5L116.402 235.5C118.008 238.283 120.987 240 124.2 240H217.797C221.017 240 223.989 238.283 225.595 235.5L272.389 154.5C273.996 151.717 273.996 148.283 272.389 145.5L225.595 64.5C223.989 61.7175 221.009 60 217.797 60Z"
                        fill="#C7C9FF" />
                    <path
                        d="M202.201 90H139.803C137.656 90 135.675 91.14 134.602 93L103.403 147C102.329 148.86 102.329 151.14 103.403 153L134.602 207C135.675 208.86 137.656 210 139.803 210H202.201C204.347 210 206.329 208.86 207.402 207L238.601 153C239.674 151.14 239.674 148.86 238.601 147L207.402 93C206.329 91.14 204.347 90 202.201 90Z"
                        fill="#FFD6DB" />
                    <path
                        d="M341.993 149.625H171.655L256.817 2.20508C256.607 2.07758 256.389 1.95008 256.164 1.83008L170.995 149.25L85.826 1.83008C85.6084 1.95008 85.3907 2.07758 85.1731 2.20508L170.342 149.625H0.0117188C0.0117188 149.873 0.0117188 150.128 0.0117188 150.375H170.349L85.1806 297.795C85.3907 297.923 85.6084 298.05 85.8335 298.17L171.002 150.75L256.171 298.17C256.389 298.05 256.607 297.923 256.824 297.795L171.655 150.375H341.993C341.993 150.128 341.993 149.873 341.993 149.625Z"
                        fill="white" />
                </g>
                <polygon transform="translate(10, 0)" id="hexagon"
                    points="67.5,149.5 103,30.5 223.5,60 338,149.5 148,190.5 67.5,149.5" stroke="#FFC747"
                    stroke-width="4" stroke-linejoin="round" fill="none" />
                <g transform="translate(10, 0)" id="hexagon-circles">
                    <circle cx="" cy="" r="0" fill="#FFC747" />
                    <circle cx="" cy="" r="0" fill="#FFC747" />
                    <circle cx="" cy="" r="0" fill="#FFC747" />
                    <circle cx="" cy="" r="0" fill="#FFC747" />
                    <circle cx="" cy="" r="0" fill="#FFC747" />
                    <circle cx="" cy="" r="0" fill="#FFC747" />
                </g>
            </svg>
            <div class="chart-text">
                <span data-title="1" class="text-b-20px text-item-1 text-center">자기성찰·계발</span>
                <span data-title="6" class="text-b-20px text-item-2 text-center">문화<br>향유</span>
                <span data-title="5" class="text-b-20px text-item-3 text-center">비판적·창의적사고 </span>
                <span data-title="4" class="text-b-20px text-item-4 text-center">공동체·대인관계</span>
                <span data-title="3" class="text-b-20px text-item-5 text-center">자료<br>정보활용</span>
                <span data-title="2" class="text-b-20px text-item-6 text-center">의사소통</span>
            </div>
        </div>
    </div>

    <div class="legend d-flex">
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-1"></div>
            <div class="legend-item-text">0 ~ 30</div>
        </div>
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-2"></div>
            <div class="legend-item-text">31 ~ 60</div>
        </div>
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-3"></div>
            <div class="legend-item-text">61 ~ 80</div>
        </div>
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-4"></div>
            <div class="legend-item-text">81 ~ 100</div>
        </div>
    </div>
</div>

<!-- 마름모 (사각형) -->
<div data-polygon="4" data-max-num="150"
    class="modal-shadow-style px-4 py-32 row justify-content-between  rounded-3 h-100 flex-column" hidden>
    <div class="d-flex justify-content-center p-5">
        <div class="d-flex justify-content-center chart-wrap angles-4">
            <svg id="hexagon-chart4" width="300" height="300" viewBox="0 0 300 320" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <g transform="translate(0, 10)">
                    <path
                        d="M139.393 10.6067L10.6067 139.393C4.74878 145.251 4.74878 154.749 10.6067 160.607L139.393 289.393C145.251 295.251 154.749 295.251 160.607 289.393L289.393 160.607C295.251 154.749 295.251 145.251 289.393 139.393L160.607 10.6067C154.749 4.74879 145.251 4.74878 139.393 10.6067Z"
                        fill="#F9F9F9" />
                    <path
                        d="M141.519 38.4864L38.4912 141.515C33.8049 146.201 33.8049 153.799 38.4912 158.485L141.519 261.514C146.206 266.2 153.804 266.2 158.49 261.514L261.518 158.485C266.205 153.799 266.205 146.201 261.518 141.515L158.49 38.4864C153.804 33.8001 146.206 33.8001 141.519 38.4864Z"
                        fill="#D1FBEC" />
                    <path
                        d="M143.639 66.3608L66.364 143.636C62.8493 147.151 62.8493 152.849 66.364 156.364L143.639 233.639C147.154 237.154 152.852 237.154 156.367 233.639L233.642 156.364C237.157 152.849 237.157 147.151 233.642 143.636L156.367 66.3608C152.852 62.8461 147.154 62.8461 143.639 66.3608Z"
                        fill="#C7C9FF" />
                    <path
                        d="M145.769 94.2406L94.2524 145.757C91.9093 148.1 91.9093 151.9 94.2524 154.243L145.769 205.759C148.112 208.103 151.911 208.103 154.255 205.759L205.771 154.243C208.114 151.9 208.114 148.1 205.771 145.757L154.255 94.2406C151.911 91.8974 148.112 91.8974 145.769 94.2406Z"
                        fill="#FFD6DB" />
                    <path
                        d="M293.778 149.64H150.369V6.22363C150.129 6.22363 149.889 6.22363 149.649 6.22363V149.633H6.23242C6.23242 149.873 6.23242 150.113 6.23242 150.353H149.641V293.761C149.881 293.761 150.121 293.761 150.361 293.761V150.353H293.77C293.77 150.113 293.77 149.873 293.77 149.633L293.778 149.64Z"
                        fill="white" />
                </g>
                <polygon transform="translate(0, 10)" id="hexagon"
                    points="67.5,149.5 103,30.5 223.5,60 338,149.5 148,190.5 67.5,149.5" stroke="#FFC747"
                    stroke-width="4" stroke-linejoin="round" fill="none" />
                <g transform="translate(0, 10)" id="hexagon-circles">
                    <circle cx="" cy="" r="0" fill="#FFC747" />
                    <circle cx="" cy="" r="0" fill="#FFC747" />
                    <circle cx="" cy="" r="0" fill="#FFC747" />
                    <circle cx="" cy="" r="0" fill="#FFC747" />
                </g>
            </svg>
            <div class="chart-text">
                <span data-title="1" class="text-b-20px text-item-1 text-center">과학지식</span>
                <span data-title="4" class="text-b-20px text-item-2 text-center">의사소통<br>능력</span>
                <span data-title="3" class="text-b-20px text-item-3 text-center">문제해결력</span>
                <span data-title="2" class="text-b-20px text-item-4 text-center">탐구능력</span>
            </div>

        </div>
    </div>
    <div class="legend d-flex">
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-1"></div>
            <div class="legend-item-text">0 ~ 30</div>
        </div>
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-2"></div>
            <div class="legend-item-text">31 ~ 60</div>
        </div>
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-3"></div>
            <div class="legend-item-text">61 ~ 80</div>
        </div>
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-4"></div>
            <div class="legend-item-text">81 ~ 100</div>
        </div>
    </div>
</div>


<!-- 삼각형 -->
<div data-polygon="3" data-max-num="200"
    class="modal-shadow-style px-4 py-32 row justify-content-between  rounded-3 h-100 flex-column" hidden>
    <div class="d-flex justify-content-center p-5">
        <div class="d-flex justify-content-center chart-wrap angles-3">
            <svg id="hexagon-chart3" width="382" height="300" viewBox="0 0 340 340" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <g transform="translate(0, 10)">
                    <path
                        d="M157.309 7.89281L2.14274 276.316C-3.94377 286.839 3.66042 300 15.8334 300H326.167C338.34 300 345.944 286.839 339.857 276.316L184.691 7.89281C178.604 -2.63094 163.396 -2.63094 157.309 7.89281Z"
                        fill="#F9F9F9" />
                    <path
                        d="M160.042 45.2592L35.909 259.997C31.0398 268.421 37.1263 278.945 46.8648 278.945H295.131C304.87 278.945 310.948 268.421 306.087 259.997L181.954 45.2592C177.085 36.8355 164.919 36.8355 160.05 45.2592H160.042Z"
                        fill="#D1FBEC" />
                    <path
                        d="M163.102 74.2095L62.698 247.895C59.0461 254.211 63.607 262.105 70.9108 262.105H271.71C279.014 262.105 283.575 254.211 279.923 247.895L179.52 74.2095C175.868 67.8937 166.738 67.8937 163.094 74.2095H163.102Z"
                        fill="#C7C9FF" />
                    <path
                        d="M165.838 109.893L95.0131 232.42C92.5785 236.628 95.6218 241.894 100.491 241.894H242.149C247.018 241.894 250.061 236.628 247.626 232.42L176.802 109.893C174.367 105.685 168.28 105.685 165.846 109.893H165.838Z"
                        fill="#FFD6DB" />
                    <path
                        d="M171.37 194.518V0.00585938C171.117 0.00585938 170.872 0.00585938 170.619 0.00585938V194.518L1.95898 291.774C2.07755 291.995 2.20403 292.208 2.3305 292.421L170.99 195.165L339.65 292.421C339.776 292.208 339.903 291.995 340.021 291.774L171.362 194.518H171.37Z"
                        fill="white" />
                </g>
                <g transform="translate(0, 10)">
                    <polygon id="hexagon" points="" stroke="#FFC747" stroke-width="4" stroke-linejoin="round"
                        fill="none" />
                    <g id="hexagon-circles">
                        <circle cx="" cy="" r="0" fill="#FFC747" />
                        <circle cx="" cy="" r="0" fill="#FFC747" />
                        <circle cx="" cy="" r="0" fill="#FFC747" />
                    </g>
                </g>

            </svg>

            <div class="chart-text">
                <span data-title="1" class="text-b-20px text-item-1 text-center">가치 · 태도</span>
                <span data-title="3" class="text-b-20px text-item-2 text-center">기능</span>
                <span data-title="2" class="text-b-20px text-item-3 text-center">지식</span>
            </div>

        </div>
    </div>
    <div class="legend d-flex">
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-1"></div>
            <div class="legend-item-text">0 ~ 30</div>
        </div>
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-2"></div>
            <div class="legend-item-text">31 ~ 60</div>
        </div>
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-3"></div>
            <div class="legend-item-text">61 ~ 80</div>
        </div>
        <div class="legend-item">
            <div class="legend-item-circle legend-item-circle-4"></div>
            <div class="legend-item-text">81 ~ 100</div>
        </div>
    </div>
</div>

<script>
    function calculatePoints(X, Y, R, percentages, angles) {
        if (angles == 6) {
            angles = [60, 120, 180, 240, 300, 360]
        } else if (angles == 4) {
            angles = [90, 180, 270, 360]
      } else if (angles == 3) {
        // angles = [90, 220, 320]
        angles = [90, 210, 330];
      }

      return angles.map((angle, i) => {
        const adjustedRadius = R * (percentages[i] / 100);
        const x = X + adjustedRadius * Math.cos(angle * Math.PI / 180);
        const y = Y - adjustedRadius * Math.sin(angle * Math.PI / 180);
        return `${x},${y}`;
      }).join(' ');
    }

    function animatePolygon(target, duration, el, X, Y, R, angles) {
      const startTime = performance.now();
      const start = new Array(target.length).fill(0)
      function animate(time) {
        const progress = Math.min((time - startTime) / duration, 1);
        const currentPercentages = start.map((start, index) => {
          return start + (target[index] - start) * progress;
        });

        const points = calculatePoints(X, Y, R, currentPercentages, angles);
        // console.log(points);
        $(`${el} #hexagon`).attr('points', points);

        if (progress < 1) {
          requestAnimationFrame(animate);
        } else {
          const pointsArray = points.split(' ').map(point => point.split(','));
          $(`${el} #hexagon-circles circle`).each(function (i, el) {
            $(el).attr('cx', pointsArray[i][0]);
            $(el).attr('cy', pointsArray[i][1]);
            $(el).attr('r', 6);
          });
        }
      }
      requestAnimationFrame(animate);
    }

    // animatePolygon([171, 171, 171, 171, 171, 171], 1000, "#hexagon-chart", 171, 150, 100, 6);
    // animatePolygon([150, 150, 150, 150], 1000, "#hexagon-chart-2", 150, 150, 100, 4);
    // animatePolygon([200, 200, 200], 0, "#hexagon-chart-3", 171, 195, 100, 3);

function getPolygonSelect(num,data1 ){
    // num = 몇각형인지, 3, 4, 6 만 허용
    // data1 = 글자 데이터
    // data2 = 수치 퍼센트.

    document.querySelectorAll('[data-polygon]').forEach((el) => {
        el.hidden = true;
    });
    const polygon_div = document.querySelector(`[data-polygon="${num}"]`);
    polygon_div.hidden = false;
    const max = polygon_div.dataset.maxNum;
    const persents = [];
    data1.forEach(function(d, idx){
        polygon_div.querySelector(`[data-title="${(idx+1)}"]`).innerText = d.code_name;
        // data2[idx] 백분률
        let insert_num = max * (d.rate/100);
        // 소수점 버리기.
        insert_num = Math.floor(insert_num);
        persents.push(insert_num);
    });
    if(num == '6')
        animatePolygon(persents, 1000, `#hexagon-chart${num}`, 171, 150, 100, 6);
    else if(num == '4')
        animatePolygon(persents, 1000, `#hexagon-chart${num}`, 150, 150, 100, 4);
    else if(num == '3')
        animatePolygon(persents, 1000, `#hexagon-chart${num}`, 171, 195, 100, 3);
}
</script>

</html>
