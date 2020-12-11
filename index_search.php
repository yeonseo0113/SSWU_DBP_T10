<?php
$link = mysqli_connect('localhost', 'root', 'rootroot', 'dbp');

$filtered_id = mysqli_real_escape_string($link, $_GET['city']);
$filtered_gu = mysqli_real_escape_string($link, $_GET['gu']);

if (isset($_GET['disabled'])) {
    $filtered_disabled = mysqli_real_escape_string($link, $_GET['disabled']);
    $query = "select isPublic from toilet where regexp_like(road_addr,'{$filtered_gu}') && (m_d_toilet != 0 || w_d_toilet != 0)";
} else {
    $filtered_disabled = 'nondisabled';
    $query = "select isPublic from toilet where regexp_like(road_addr,'{$filtered_gu}')";
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$result = mysqli_query($link, $query);

$row_num = mysqli_num_rows($result); //게시판 총 레코드 수
$list = 5; //한 페이지에 보여줄 개수
$block_ct = 5; //블록당 보여줄 페이지 개수

$page_num = ceil($row_num/$list); //총 페이지
$block_num = ceil($page_num/$block_ct); //총 블럭
$nowBlock = ceil($page/$block_ct); //현재 페이지가 위차한 블록 번호

$block_num = ceil($page/$block_ct); // 현재 페이지 블록 구하기
$block_start = (($block_num - 1) * $block_ct) + 1; // 블록의 시작번호
$block_end = $block_start + $block_ct - 1; //블록 마지막 번호

$total_page = ceil($row_num / $list); // 페이징한 페이지 수 구하기
if ($block_end > $total_page) {
    $block_end = $total_page;
} //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
$total_block = ceil($total_page/$block_ct); //블럭 총 개수
$start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

$prevBlock = $nowBlock-1;
$nextBlock = $nowBlock+1;

// if (empty($filtered_disabled)) {
if (strstr($filtered_disabled, 'nondisabled')) {
    $query3 =   "select isPublic, toilet_name, road_addr, isUnisex, m_d_toilet, w_d_toilet, m_k_toilet, w_k_toilet, open_time from toilet where regexp_like(road_addr, '{$filtered_gu}') limit {$start_num}, {$list}";
    $result3 = mysqli_query($link, $query3) or die(mysqli_error($link));
    $gu_info = '';
    while ($row = mysqli_fetch_array($result3)) {
        $gu_info .= '<tr>';
        $gu_info .= '<td>'.$row['isPublic'].'</td>';
        $gu_info .= '<td>'.$row['toilet_name'].'</td>';
        $gu_info .= '<td>'.$row['road_addr'].'</td>';
        $gu_info .= '<td>'.$row['isUnisex'].'</td>';
        $gu_info .= '<td>'.$row['m_d_toilet'].'</td>';
        $gu_info .= '<td>'.$row['w_d_toilet'].'</td>';
        $gu_info .= '<td>'.$row['m_k_toilet'].'</td>';
        $gu_info .= '<td>'.$row['w_k_toilet'].'</td>';
        $gu_info .= '<td>'.$row['open_time'].'</td>';
        $gu_info .= '</tr>';
    }
} else {
    $query3 = "select isPublic, toilet_name, road_addr, isUnisex, m_d_toilet, w_d_toilet, m_k_toilet, w_k_toilet, open_time from toilet where regexp_like(road_addr, '{$filtered_gu}') && (m_d_toilet != 0 || w_d_toilet != 0) limit {$start_num}, {$list}";
    $result3 = mysqli_query($link, $query3) or die(mysqli_error($link));
    $gu_info = '';
    while ($row = mysqli_fetch_array($result3)) {
        $gu_info .= '<tr>';
        $gu_info .= '<td>'.$row['isPublic'].'</td>';
        $gu_info .= '<td>'.$row['toilet_name'].'</td>';
        $gu_info .= '<td>'.$row['road_addr'].'</td>';
        $gu_info .= '<td>'.$row['isUnisex'].'</td>';
        $gu_info .= '<td>'.$row['m_d_toilet'].'</td>';
        $gu_info .= '<td>'.$row['w_d_toilet'].'</td>';
        $gu_info .= '<td>'.$row['m_k_toilet'].'</td>';
        $gu_info .= '<td>'.$row['w_k_toilet'].'</td>';
        $gu_info .= '<td>'.$row['open_time'].'</td>';
        $gu_info .= '</tr>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <style>
  table.list-table {
    border-collapse: collapse;
    text-align: center;
    line-height: 1;
  }
  table.list-table thead th {
    /* border-top: 1px solid #787878; */
    padding: 10px;
    font-weight: bold;
    vertical-align: center;
    color: black;
    border-bottom: 2px solid #288C28;
    text-align: center;
  }
  table.list-table tbody th {
    width: 150px;
    padding: 10px;
    font-weight: bold;
    vertical-align: center;
    border-bottom: 1px solid #ccc;
    background: #f3f6f7;
    text-align: center;
  }
  table.list-table td {
    color: #787878;
    padding: 10px;
    vertical-align: center;
    border-bottom: 1px solid #ccc;\
    text-align: center;
  }
</style>
  <meta charset="utf-8">
  <script>
  let now;

  function fn_clickView(e){
    let target = e.target;
    // 이미지 아니면 실행 취소
    if(target.tagName != 'IMG'){
      return;
    }
    // 선택한거 있을시 끔
    if(now != undefined){
      fn_clickOff();
    }
    // 선택한거 바꿈
    now = target;
    target.src = target.src.slice(0, target.src.length-7) + 'on.png' // 주소변경
  }
  // 선택된거 끄는 코드
  function fn_clickOff(){
    now.src = now.src.slice(0, now.src.length-6) + 'off.png' // 주소변경
  }
  </script>

  <script type="text/javascript" src="./map.js"></script>

</head>

<body>
  <style>

  .subContent { position: relative; width: 1200px; margin: 0 auto;}
  .subContent.subMain {width:100%;}
  .sub { width:100%;}
  .sub:after {
    display:block; content:""; clear:both;
  }

  .center {
    border:1px solid #ddd;
    width:100%;
    margin-bottom:10px;
    height:600px
  }

  .center-block {
    display: block;
    margin-left: auto;
    margin-right: auto
  }

  .mapsearch {
    width:100%;
    border:#c3c3c3 1px solid;
    display: block;
    position: relative;
    overflow: hidden;
    background: url("images/img_002.png") no-repeat scroll 0 0 transparent;
    background-color:rgba(241, 241, 240, 1); box-sizing:border-box;
  }

  .mapsearch .linkbanners {
    width:440px;
    display: block;
    overflow: hidden;
    float: right;
    margin:80px 20px;
  }

  .mapsearch .linkbanners h2 {
    display: block;
    position: relative;
    overflow: hidden;
    font-size:24px; color:#02ad02;
    font-weight: 700;
    background: url("images/img_001.png") no-repeat scroll 0 -99px transparent;
    padding:0 0 10px 30px;
    min-height:57px;
  }

  .mapsearch .linkbanners .txt_01 {
    width:439px;
    height:376px;
    display: block;
    float: left;
    overflow: hidden;
    background:#fff;
    border:#c3c3c3 1px solid;
  }

  .mapsearch .linkbanners .txt_01 ul li {
    width:200px;
    height:60px;
    background-color:#fff;
    float: left;
    margin:2px 0 0 11px;
    border:#d3d3d3 1px solid;
    font-size: 0;
    line-height: 0;
    text-indent: -9999px;
  }

  .sub_title_wrap {
    text-align:center;
    margin-bottom:25px;
  }
  .sub_title_wrap h2 {
    text-align:center;
    font-size:38px; color:#000;
    margin:0;
    line-height:1;
    font-family: 'Nanum Gothic Bold';
  }
  .sub_title_wrap > span {
    display:block;
    margin-top:25px;
    font-size:16px;
    color:#666;
    line-height:1.5;
  }
  .sub_title_wrap > span.desc_type2 {
    margin-top:10px;
    font-size:14px;
  }
  .alert-info {
    background-color: #d9edf7;
    border-color: #bce8f1;
    color: #31708f
  }
  a{
    margin: 0px;
    padding: 0px;
    border: 0px;
  }
  .table{
    text-align: center;
  }
  .search{
    text-align: center;
  }
  #search{
text-align:center;
}

.search1{
display:inline-block;zoom:1;.display:inline;
}
  </style>

  <article class="subContent" id="contList">
    <section class="sub">
      <div class="sub_title_wrap">
        <h2> 공중화장실 안내</h2>
        <div class="alert alert-info" role="alert" id="alert" style="margin-top: 20px;">
          본 사이트에서 제공하는 공중화장실은 공공데이터 포털 개방자료 활용, 자세한 내용 및 원본은 각 지자체 홈페이지를 참고하시기 바랍니다.
        </div>
      </div>

      <div  class="center" id = "viewToilet">
        <div class="mapsearch">
          <!--서울특별시-->
          <a href="#" id="map_btn_01" onclick="fn_clickView(event);" style="position:absolute; left:162px; top:76px; z-index:99;">
            <img src="./images/map_1_off.png" id="local_01" name="local_01" alt="서울특별시">
          </a>
          <div id="map_01" class="linkbanners" style="display: block;">
            <h2>서울특별시</h2>
            <div class="txt_01">
              <table style="width:100%;">
                <colgroup>
                  <col width="50%">
                  <col width="50%">
                </colgroup>
                <tbody>
                  <tr>
                    <td style="height:40px; text-align:center; font-size:15px;
                    border-right:1px solid #c8c8c8; border-bottom:1px solid #c8c8c8;">
                    <a href="#" title="새창열림" target="_blank">화장실1</a>
                  </td>
                  <td style="height:40px; text-align:center; font-size:15px;
                  border-right:1px solid #c8c8c8;  border-bottom:1px solid #c8c8c8;">
                  <a href="#" title="새창열림" target="_blank">화장실2</a>
                </td>
              </tr>
              <tr>
                <td style="height:40px; text-align:center; font-size:15px;
                border-right:1px solid #c8c8c8; border-bottom:1px solid #c8c8c8;">
                <a href="#" onclick="javascript:fn_notPage(); return false;">화장실3</a>
              </td>
              <td style="height:40px; text-align:center; font-size:15px;
              border-right:1px solid #c8c8c8; border-bottom:1px solid #c8c8c8;">
              <a href="#" onclick="javascript:fn_notPage(); return false;">화장실4</a>
            </td>
          </tr>
        </tbody>
      </table>
      <div id="mapPage" style="text-align:center;margin:15px 0 0 0;">
        <span style="margin-left:5px">1</span>
        <span style="margin-left:5px">
          <a href="#" onclick="goPage(2); return false;">[2]</a>
        </span>
      </div>
    </div>
  </div>

  <!--인천-->
  <a href="##" id="map_btn_02" onclick="fn_clickView(event);" style="position:absolute; left:64px; top:107px; z-index:99;">
    <img src="./images/map_2_off.png" id="local_02" alt="인천">
  </a>
  <div id="map_02" class="linkbanners" style="display: none;">
    <h2>인천광역시</h2>
    <div class="txt_01"></div>
    <table style="width:100%;">
      <colgroup>
        <col width="50%">
        <col width="50%">
      </colgroup>
      <tbody>
        <tr>
          <td style="height:40px; text-align:center; font-size:15px;
          border-right:1px solid #c8c8c8; border-bottom:1px solid #c8c8c8;">
          <a href="#" title="새창열림" target="_blank">화장실4</a>
        </td>
        <td style="height:40px; text-align:center; font-size:15px;
        border-right:1px solid #c8c8c8;  border-bottom:1px solid #c8c8c8;">
        <a href="#" title="새창열림" target="_blank">화장실5</a>
      </td>
    </tr>
    <tr>
      <td style="height:40px; text-align:center; font-size:15px;
      border-right:1px solid #c8c8c8; border-bottom:1px solid #c8c8c8;">
      <a href="#" onclick="javascript:fn_notPage(); return false;">화장실6</a>
    </td>
    <td style="height:40px; text-align:center; font-size:15px;
    border-right:1px solid #c8c8c8; border-bottom:1px solid #c8c8c8;">
    <a href="#" onclick="javascript:fn_notPage(); return false;">화장실7</a>
  </td>
</tr>
</tbody>
</table>
<div id="mapPage" style="text-align:center;margin:15px 0 0 0;">
  <span style="margin-left:5px">1</span>
  <span style="margin-left:5px">
    <a href="#" onclick="goPage(2); return false;">[2]</a>
  </span>
</div>
</div>

<!--경기도-->
<a href="###" id="map_btn_03" onclick="fn_clickView(event);" style="position:absolute; left:153px; top:40px; z-index:98;">
  <img src="images/map_3_off.png" id="local_03" alt="경기도">
</a>
<div id="map_03" class="linkbanners" style="display: none;">
  <h2>경기도</h2>
  <div class="txt_01"></div>
</div>

<!--강원도-->
<a href="####" id="map_btn_04" onclick="fn_clickView(event);" style="position:absolute; left:238px; top:5px;">
  <img src="images/map_4_off.png" id="local_04" alt="강원도">
</a>
<div id="map_04" class="linkbanners" style="display: none;">
  <h2>강원도</h2>
  <div class="txt_01"></div>
</div>

<!--충남-->
<a href="#####" id="map_btn_05" onclick="fn_clickView(event);" style="position:absolute; left:134px; top:171px; z-index:98;">
  <img src="images/map_5_off.png" id="local_05" alt="충남">
</a>
<div id="map_05" class="linkbanners" style="display:none;">
  <h2>충청남도</h2>
  <div class="txt_01"></div>
</div>

<!--경북-->
<a href="#" id="map_btn_06" onclick="fn_clickView(event);" style="position:absolute; left:287px; top:181px; z-index:98;">
  <img src="images/map_6_off.png" id="local_06" alt="경북">
</a>
<div id="map_06" class="linkbanners" style="display: none;">
  <h2>경상북도</h2>
  <div class="txt_01"></div>
</div>

<!--대전-->
<a href="#" id="map_btn_07" onclick="fn_clickView(event);" style="position:absolute; left:192px; top:214px; z-index:99;">
  <img src="images/map_7_off.png" id="local_07" alt="대전">
</a>
<div id="map_07" class="linkbanners" style="display:none;">
  <h2>대전광역시</h2>
  <div class="txt_01"></div>
</div>

<!--대구-->
<a href="#" id="map_btn_08" onclick="fn_clickView(event);" style="position:absolute; left:298px; top:282px; z-index:99;">
  <img src="images/map_8_off.png" id="local_08" alt="대구">
</a>
<div id="map_08" class="linkbanners" style="display:none;">
  <h2>대구광역시</h2>
  <div class="txt_01"></div>
</div>

<!--전북-->
<a href="#" id="map_btn_09" onclick="fn_clickView(event);" style="position:absolute; left:151px; top:275px; z-index:98;">
  <img src="images/map_9_off.png" id="local_09" alt="전북">
</a>
<div id="map_09" class="linkbanners" style="display:none;">
  <h2>전라북도</h2>
  <div class="txt_01"></div>
</div>

<!--울산-->
<a href="#" id="map_btn_10" onclick="fn_clickView(event);" style="position:absolute; left:362px; top:332px; z-index:99;">
  <img src="images/map_10_off.png" id="local_10" alt="울산">
</a>
<div id="map_10" class="linkbanners" style="display:none;">
  <h2>울산광역시</h2>
  <div class="txt_01"></div>
</div>

<!--경남-->
<a href="#" id="map_btn_11" onclick="fn_clickView(event);" style="position:absolute; left:255px; top:310px; z-index:98;">
  <img src="images/map_11_off.png" id="local_11" alt="경남">
</a>
<div id="map_11" class="linkbanners" style="display:none;">
  <h2>경상남도</h2>
  <div class="txt_01"></div>
</div>

<!--부산-->
<a href="#" id="map_btn_12" onclick="fn_clickView(event);" style="position:absolute; left:370px; top:385px; z-index:99;">
  <img src="images/map_12_off.png" id="local_12" alt="부산">
</a>
<div id="map_12" class="linkbanners" style="display:none;">
  <h2>부산광역시</h2>
  <div class="txt_01"></div>
</div>

<!--충북-->
<a href="#" id="map_btn_13" onclick="fn_clickView(event);" style="position:absolute; left:240px; top:160px; z-index:98;">
  <img src="images/map_13_off.png" id="local_13" alt="충북">
</a>
<div id="map_13" class="linkbanners" style="display:none;">
  <h2>충청북도</h2>
  <div class="txt_01"></div>
</div>

<!--세종특별시-->
<a href="#" id="map_btn_14" onclick="fn_clickView(event);" style="position:absolute; left:56px; top:248px; z-index:99;">
  <img src="images/map_14_off.png" id="local_14" alt="세종특별자치시">
</a>
<div id="map_14" class="linkbanners" style="display: none;">
  <h2>세종특별자치시</h2>
  <div class="txt_01"></div>
</div>

<!--전라남도-->
<a href="#" id="map_btn_15" onclick="fn_clickView(event);" style="position:absolute; left:89px; top:349px; z-index:98;">
  <img src="images/map_15_off.png" id="local_15" alt="전라남도">
</a>
<div id="map_15" class="linkbanners" style="display:none;">
  <h2>전라남도</h2>
  <div class="txt_01"></div>
</div>

<!--제주도-->
<a href="#" id="map_btn_16" onclick="fn_clickView(event);" style="position:absolute; left:103px; top:495px; z-index:98;">
  <img src="images/map_16_off.png" id="local_16" alt="제주특별자치도">
</a>
<div id="map_16" class="linkbanners" style="display:none;">
  <h2>제주특별자치도</h2>
  <div class="txt_01"></div>
</div>

<!--광주-->
<a href="#" id="map_btn_17" onclick="fn_clickView(event);" style="position:absolute; left:130px; top:340px; z-index:99;">
  <img src="images/map_17_off.png" id="local_17" alt="광주">
</a>
<div id="map_17" class="linkbanners" style="display:none;">
  <h2>광주광역시</h2>
  <div class="txt_01"></div>
</div>
</div>
</div>
</article>
<br/><br/><br/>
<div id = "search">
<div class="search1">
  <form action="index_search.php" method="GET"> 지역 검색
    <select onchange="categoryChange(this)" name="city">
      <option value="Every">전체</option>
      <option value="서울특별시" name ="서울특별시" >서울특별시</option>
      <option value="부산광역시" name ="부산광역시">부산광역시</option>
      <option value="대구광역시" name ="대구광역시">대구광역시</option>
      <option value="인천광역시" name ="인천광역시">인천광역시</option>
      <option value="광주광역시" name ="광주광역시">광주광역시</option>
      <option value="대전광역시"name ="대전광역시">대전광역시</option>
      <option value="울산광역시"name ="울산광역시">울산광역시</option>
      <option value="세종특별자치시"name ="세종특별자치시">세종특별자치시</option>
      <option value="경기도"name ="경기도">경기도</option>
      <option value="강원도"name ="강원도">강원도</option>
      <option value="충청북도"name ="충청북도">충정북도</option>
      <option value="충청남도"name ="충청남도">충청남도</option>
      <option value="전라북도"name ="전라북도">전라북도</option>
      <option value="전라남도"name ="전라남도">전라남도</option>
      <option value="경상북도"name ="경상북도">경상북도</option>
      <option value="경상남도"name ="경상남도">경상남도</option>
      <option value="제주특별자치도"name ="제주특별자치도">제주특별자치도</option>
    </select>
    <select id="area" name="gu">
      <option value="Every">전체</option>
    </select> 장애인용 화장실
    <input type="checkbox" name="disabled" value="disabled">
    <input type="submit" value="검색">
    </form>
  </div>
      <div class="search1" ><a href="index.php"><Button>초기화</Button></a></div>
    </div>
<div>
  <table class="list-table"  align = center>
    <thead>
      <tr>
        <th>구분</th>
        <th>이름</th>
        <th>주소</th>
        <th>남녀공용</th>
        <th>장애인용변기(남)</th>
        <th>장애인용변기(여)</th>
        <th>어린이용변기(남)</th>
        <th>어린이용변기(여)</th>
        <th>개방시간</th>
      </tr>
    </thead>
    <tbody>
      <?= $gu_info ?>
    </tbody>
  </table>
  <!---페이징 넘버 --->
  <div id="page_num" class="search">
    <?php
    if ($page <= 1) { //만약 page가 1보다 크거나 같다면
      echo "<span class='fo_re'>처음</span>"; //처음이라는 글자에 빨간색 표시
    } else {
        echo "<a href='?page=1&city={$filtered_id}&gu={$filtered_gu}&disabled={$filtered_disabled}'> 처음 </a>"; // 처음글자에 1번페이지로 갈 수있게 링크
    }
    if ($page <= 1) { //만약 page가 1보다 크거나 같다면 빈값
    } else {
        $pre = $page-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
      echo "<a href='?page=$pre&city={$filtered_id}&gu={$filtered_gu}&disabled={$filtered_disabled}'> 이전 </a>"; //이전글자에 pre변수를 링크한다. 이러면 이전버튼을 누를때마다 현재 페이지에서 -1하게 된다.
    }
    for ($i=$block_start; $i<=$block_end; $i++) {
        //for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
      if ($page == $i) { //만약 page가 $i와 같다면
        echo "<span class='fo_re'>[$i]</span>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
      } else {
          echo "<a href='?page=$i&city={$filtered_id}&gu={$filtered_gu}&disabled={$filtered_disabled}'>[$i]</a>"; //아니라면 $i
      }
    }
    if ($block_num >= $total_block) { //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
    } else {
        $next = $page + 1; //next변수에 page + 1을 해준다.
      echo "<a href='?page=$next&city={$filtered_id}&gu={$filtered_gu}&disabled={$filtered_disabled}'> 다음 </a>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
    }
    if ($page >= $total_page) { //만약 page가 페이지수보다 크거나 같다면
      echo "<span class='fo_re'> 마지막 </span>"; //마지막 글자에 긁은 빨간색을 적용한다.
    } else {
        echo "<a href='?page=$total_page&city={$filtered_id}&gu={$filtered_gu}&disabled={$filtered_disabled}'> 마지막 </a>"; //아니라면 마지막글자에 total_page를 링크한다.
    }
    ?>
  </div>
</div>
</article>
</body>
</html>
<script>
function categoryChange(e) {

  var every = ["전체"];
  var Seoul = ["강남구","강동구","강북구","강서구","관악구","광진구","구로구","금천구","노원구","도봉구","동대문구","동작구","마포구","서대문구","서초구","성동구","성북구","송파구","양천구","영등포","구용산구","은평구","종로구","중구","중랑구"];
  var Busan = ["강서구","금정구","남구","동구","동래구","진구","북구","사상구","사하구","서구","수영구","연제구","영도구","중구","해운대구"];
  var Daegu = ["남구","달서구","동구","북구","서구","수성구","중구"];
  var Incheon = ["계양구","남동구","동구","미추홀구","부평구","서구","연수구","중구","강화군","옹진군"];
  var Gwangju = ["광산구","남구","동구","북구","서구"];
  var Daejeon = ["대덕구","동구","서구","유성구","중구"];
  var Ulsan = ["남구","동구","북구","중구","울주군"];
  var Sejong = ["조치원읍","금남면","부강면","소정면","연기면","연동면","연서면","장군면","전동면","전의면","고운동","다정동","대평동","도담동","보람동","소담동","새롬동","아름동","종촌동","한솔동"];
  var Gyeonggi = ["고양시","과천시","광명시","광주시","구리시","군포시","김포시","남양주시","동두천시","부천시","성남시","수원시","시흥시","안산시","안성시","안양시","양주시","여주시","오산시","용인시","의왕시","의정부시","이천시","파주시","평택시","포천시","하남시","화성시",
  "가평군","양평군","연천군"];
  var Gangwon = ["강릉시","동해시","삼척시","속초시","원주시","춘천시","태백시","고성군","양구군","양양군","영월군","인제군","정선군","철원군","평창군","홍천군","화천군","횡성군"];
  var Chungbuk = ["제천시","청주시","충주시","괴산군","단양군","보은군","영동군","옥천군","음성군","증평군","진천군"];
  var Chungnam = ["계룡시","공주시","논산시","당진시","보령시","서산시","아산시","천안시","금산군","부여군","서천군","예산군","청양군","태안군","홍성군"];
  var Jeonbuk = ["군산시","김제시","남원시","익산시","전주시","정읍시","고창군","무주군","부안군","순창군","완주군","임실군","장수군","진안군"];
  var Jeonnam = ["광양시","나주시","목포시","순천시","여수시","강진군","고흥군","곡성군","구례군","담양군","무안군","보성군","신안군","영광군","영암군","도군장","성군장","흥군진","도군함","평군해","남군화","순군"];
  var GyeongBuk = ["경산시","경주시","구미시","김천시","문경시","상주시","안동시","영주시","영천시","포항시","고령군","군위군","봉화군","성주군","영덕군","영양군","예천군","울릉군","울진군","의성군","청도군","청송군","칠곡군"];
  var Gyeongnam = ["거제시","김해시","밀양시","사천시","양산시","진주시","창원시","통영시","거창군","고성군","남해군","산청군","의령군","창녕군","하동군","함안군","함양군","합천군",];
  var Jeju = ["제주시","서귀포시"];
  var target = document.getElementById("area");

  if(e.value == "서울특별시") var d = Seoul;
  else if(e.value == "부산광역시") var d = Busan;
  else if(e.value == "대구광역시") var d = Daegu;
  else if(e.value == "인천광역시") var d = Incheon;
  else if(e.value == "광주광역시") var d = Gwangju;
  else if(e.value == "대전광역시") var d = Daejeon;
  else if(e.value == "울산광역시") var d = Ulsan;
  else if(e.value == "세종특별자치시") var d = Sejong;
  else if(e.value == "경기도") var d = Gyeonggi;
  else if(e.value == "강원도") var d = Gangwon;
  else if(e.value == "충청북도") var d = Chungbuk;
  else if(e.value == "충청남도") var d = Chungnam;
  else if(e.value == "전라북도") var d = Jeonbuk;
  else if(e.value == "전라남도") var d = Jeonnam;
  else if(e.value == "경상북도") var d = GyeongBuk;
  else if(e.value == "경상남도") var d = Gyeongnam;
  else if(e.value == "제주특별자치도") var d = Jeju;
  else if(e.value == "Every") var d = every;

  target.options.length = 0;

  for (x in d) {
    var opt = document.createElement("option");
    opt.value = d[x];
    // opt.name = d[x];
    opt.innerHTML = d[x];
    target.appendChild(opt);
  }
}
</script>
