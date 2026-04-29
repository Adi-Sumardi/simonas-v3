@extends('mentor.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Dashboard</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                      <i class="fas fa-list-ol"></i>
                    </div>
                    <div class="card-wrap">
                      <div class="card-header">
                        <h4>Data Kegiatan</h4>
                      </div>
                      <div class="card-body">
                       {{ $kegiatan }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                      <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                      <div class="card-header">
                        <h4>Data Warga</h4>
                      </div>
                      <div class="card-body">
                        {{ $user }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                      <i class="far fa-id-badge"></i>
                    </div>
                    <div class="card-wrap">
                      <div class="card-header">
                        <h4>Data Alumni</h4>
                      </div>
                      <div class="card-body">
                        {{ $alumni }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                      <i class="fas fa-paste"></i>
                    </div>
                    <div class="card-wrap">
                      <div class="card-header">
                        <h4>Direktur & Ketua</h4>
                      </div>
                      <div class="card-body">
                        {{ $asrama}}
                      </div>
                    </div>
                  </div>
                </div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                  <div class="card-icon bg-info">
                    <i class="fas fa-medal"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>Data Akademik</h4>
                    </div>
                    <div class="card-body">
                     {{ $akademik }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                  <div class="card-icon bg-success">
                    <i class="far fa-thumbs-up"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>Data Leadership</h4>
                    </div>
                    <div class="card-body">
                      {{ $leadership }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                  <div class="card-icon bg-secondary">
                    <i class="fas fa-mosque"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>Data Karakter Islam</h4>
                    </div>
                    <div class="card-body">
                      {{ $karakter }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                  <div class="card-icon bg-warning">
                    <i class="fas fa-icons"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>Data Kreatifitas & Wirausaha</h4>
                    </div>
                    <div class="card-body">
                      {{ $kreatif }}
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>

        <div class="col-12 col-md-12 col-lg-12">
          <canvas id="myChart"></canvas>
        </div>

<script>
  // <block:setup:1>
const labels = [
  'January',
  'February',
  'March',
  'April',
  'May',
  'June',
];
const data = {
  labels: labels,
  datasets: [{
    label: 'Data Pengisian Daily Report',
    backgroundColor: 'rgb(255, 99, 132)',
    borderColor: 'rgb(255, 99, 132)',
    data: [0, 10, 5, 2, 20, 30, 45],
  }]
};
// </block:setup>

// <block:config:0>
const config = {
  type: 'line',
  data,
  options: {}
};
// </block:config>

module.exports = {
  actions: [],
  config: config,
};
</script>
    </section>
@endsection

