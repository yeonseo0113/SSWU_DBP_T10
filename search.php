<!-- search.php -->
<?php
$link = mysqli_connect('localhost:3307', 'root', 'root06', 'dbp');
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$query = "select isPublic from toilet";
$result = mysqli_query($link, $query);
// print_r($result);
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



$query2 = "select isPublic, toilet_name, road_addr, isUnisex, m_d_toilet, w_d_toilet, m_k_toilet, w_k_toilet, open_time from toilet limit {$start_num}, {$list}";
$result2 = mysqli_query($link, $query2) or die(mysqli_error($link));
$toilet_info = '';
while ($row = mysqli_fetch_array($result2)) {
    $toilet_info .= '<tr>';
    $toilet_info .= '<td>'.$row['isPublic'].'</td>';
    $toilet_info .= '<td>'.$row['toilet_name'].'</td>';
    $toilet_info .= '<td>'.$row['road_addr'].'</td>';
    $toilet_info .= '<td>'.$row['isUnisex'].'</td>';
    $toilet_info .= '<td>'.$row['m_d_toilet'].'</td>';
    $toilet_info .= '<td>'.$row['w_d_toilet'].'</td>';
    $toilet_info .= '<td>'.$row['m_k_toilet'].'</td>';
    $toilet_info .= '<td>'.$row['w_k_toilet'].'</td>';
    $toilet_info .= '<td>'.$row['open_time'].'</td>';
    $toilet_info .= '</tr>';
}

if (isset($_GET['city']) && isset($_GET['gu'])) {
    $filtered_id = mysqli_real_escape_string($link, $_GET['city']);
    $filtered_gu = mysqli_real_escape_string($link, $_GET['gu']);
    // $query3 = "select isPublic, toilet_name, road_addr, isUnisex, m_d_toilet, m_d_urinal, w_d_toilet, open_time from toilet WHERE  limit {$start_num}, {$list}";
    $query3 =   "select isPublic, toilet_name, road_addr, isUnisex, m_d_toilet, w_d_toilet, m_k_toilet, w_k_toilet, open_time from toilet where regexp_like(road_addr, '{$filtered_gu}') limit {$start_num}, {$list}";
    $result3 = mysqli_query($link, $query3) or die(mysqli_error($link));
    $toilet_info = '';
    while ($row = mysqli_fetch_array($result3)) {
        $toilet_info .= '<tr>';
        $toilet_info .= '<td>'.$row['isPublic'].'</td>';
        $toilet_info .= '<td>'.$row['toilet_name'].'</td>';
        $toilet_info .= '<td>'.$row['road_addr'].'</td>';
        $toilet_info .= '<td>'.$row['isUnisex'].'</td>';
        $toilet_info .= '<td>'.$row['m_d_toilet'].'</td>';
        $toilet_info .= '<td>'.$row['w_d_toilet'].'</td>';
        $toilet_info .= '<td>'.$row['m_k_toilet'].'</td>';
        $toilet_info .= '<td>'.$row['w_k_toilet'].'</td>';
        $toilet_info .= '<td>'.$row['open_time'].'</td>';
        $toilet_info .= '</tr>';
    }
} elseif (isset($_GET['city'])) {
    // 특별시 선택할 때
    $filtered_id = mysqli_real_escape_string($link, $_GET['city']);
    // $query3 = "select isPublic, toilet_name, road_addr, isUnisex, m_d_toilet, m_d_urinal, w_d_toilet, open_time from toilet WHERE  limit {$start_num}, {$list}";
    $query3 = "select isPublic, toilet_name, road_addr, isUnisex, m_d_toilet, w_d_toilet,m_k_toilet,w_k_toilet,open_time from toilet where regexp_like(road_addr, {$filtered_id}) limit {$start_num}, {$list}";

    $result3 = mysqli_query($link, $query3) or die(mysqli_error($link));
    $city_info = '';

    while ($row = mysqli_fetch_array($result3)) {
        $city_info .= '<tr>';
        $city_info .= '<td>'.$row['isPublic'].'</td>';
        $city_info .= '<td>'.$row['toilet_name'].'</td>';
        $city_info .= '<td>'.$row['road_addr'].'</td>';
        $city_info .= '<td>'.$row['isUnisex'].'</td>';
        $city_info .= '<td>'.$row['m_d_toilet'].'</td>';
        $city_info .= '<td>'.$row['w_d_toilet'].'</td>';
        $city_info .= '<td>'.$row['m_k_toilet'].'</td>';
        $city_info .= '<td>'.$row['w_k_toilet'].'</td>';
        $city_info .= '<td>'.$row['open_time'].'</td>';
        $city_info .= '</tr>';
    }
}

?>
<!-- 지역, 이름, 주소, 남녀공용여부, 남여장애인화장실여부, 시간 -->
<!doctype html>
<head>
  <meta charset="UTF-8">
  <title>페이징</title>
  <link rel="stylesheet" type="text/css" href="/css/style.css" />
  <style>
  #page_num {
    font-size: 14px;
    margin-left: 260px;
    margin-top:30px;
  }
  #page_num ul li {
    float: left;
    margin-left: 10px;
    text-align: center;
  }
  .fo_re {
    font-weight: bold;
    color:red;
  }
  a{text-decoration: none}

</style>
</head>
<body>
  <div>
    <form action="search_city.php" method="GET"> 지역 검색
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
    <a href="search.php"><Button>초기화</Button></a>

  </div>
  <div>
    <table border=1 class="list-table">
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
        <?= $toilet_info ?>
      </tbody>
    </table>
    <!---페이징 넘버 --->
    <div id="page_num">
      <?php
      if ($page <= 1) { //만약 page가 1보다 크거나 같다면
        echo "<span class='fo_re'>처음</span>"; //처음이라는 글자에 빨간색 표시
      } else {
          echo "<a href='?page=1'> 처음 </a>"; //알니라면 처음글자에 1번페이지로 갈 수있게 링크
      }
      if ($page <= 1) { //만약 page가 1보다 크거나 같다면 빈값
      } else {
          $pre = $page-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
        echo "<a href='?page=$pre'> 이전 </a>"; //이전글자에 pre변수를 링크한다. 이러면 이전버튼을 누를때마다 현재 페이지에서 -1하게 된다.
      }
      for ($i=$block_start; $i<=$block_end; $i++) {
          //for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
        if ($page == $i) { //만약 page가 $i와 같다면
          echo "<span class='fo_re'>[$i]</span>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
        } else {
            echo "<a href='?page=$i'>[$i]</a>"; //아니라면 $i
        }
      }
      if ($block_num >= $total_block) { //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
      } else {
          $next = $page + 1; //next변수에 page + 1을 해준다.
        echo "<a href='?page=$next'> 다음 </a>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
      }
      if ($page >= $total_page) { //만약 page가 페이지수보다 크거나 같다면
        echo "<span class='fo_re'> 마지막 </span>"; //마지막 글자에 긁은 빨간색을 적용한다.
      } else {
          echo "<a href='?page=$total_page'> 마지막 </a>"; //아니라면 마지막글자에 total_page를 링크한다.
      }
      ?>
    </div>
  </div>
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
