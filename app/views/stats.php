<div class="container-fluid mb-5 bg-white">
    <div class="row justify-content-center pb-5">
        <div class="col-12 p-4">

            <div class="row">
                <div class="col">
                    <h4><strong>Dados estatísticos</strong></h4>
                </div>
                <div class="col text-end">
                    <a href="?ct=main&mt=index" class="btn btn-secondary px-4"><i class="fa-solid fa-chevron-left me-2"></i>Voltar</a>
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-sm-6 col-12 p-1">
                    <div class="card p-3">
                        <h4><i class="fa-solid fa-users me-2"></i>Clientes dos agentes</h4>
                        <?php if (count($agents) == 0) : ?>
                            <p class="text-center">Não foram encontrados dados.</p>
                        <?php else : ?>
                            <table class="table table-striped table-bordered" id="table_agents">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Agente</th>
                                        <th class="text-center">Clientes registados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($agents as $agent) : ?>
                                        <tr>
                                            <td><?= $agent->agente ?></td>
                                            <td class="text-center"><?= $agent->total_clientes ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-sm-6 col-12 p-1">
                    <div class="card p-3">
                        <h4><i class="fa-solid fa-users me-2"></i>Gráfico</h4>
                        <canvas id="chartjs_chart" height="400px"></canvas>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col p-1">

                    <div class="card p-3">
                        <h4><i class="fa-solid fa-list-ul me-2"></i>Dados estatísticos globais</h4>

                        <div class="row justify-content-center">
                            <div class="col-5">
                                <table class="table table-striped">
                                    <tr>
                                        <td class="text-start">Número total de agentes:</td>
                                        <td class="text-start"><strong><?= $global_stats['total_agents']->value ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Número total de clientes:</td>
                                        <td class="text-start"><strong><?= $global_stats['total_clients']->value ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Número total de clientes inativos:</td>
                                        <td class="text-start"><strong><?= $global_stats['total_deleted_clients']->value ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Número médio de clientes por agente:</td>
                                        <td class="text-start"><strong><?= sprintf("%d", $global_stats['average_clients_per_agent']->value) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Idade do cliente mais novo:</td>
                                        <td class="text-start"><strong><?= $global_stats['younger_client']->value ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Idade do cliente mais velho:</td>
                                        <td class="text-start"><strong><?= $global_stats['oldest_client']->value ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Média de idades dos clientes:</td>
                                        <td class="text-start"><strong><?= sprintf("%.2f", $global_stats['average_age']->value) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Percentagem de clientes homens:</td>
                                        <td class="text-start"><strong><?= sprintf("%.2f", $global_stats['percentage_males']->value) . "%" ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Percentagem de clientes mulheres:</td>
                                        <td class="text-start"><strong><?= sprintf("%.2f", $global_stats['percentage_females']->value) . '%' ?></strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="?ct=admin&mt=create_pdf_report" target="_blank" class="btn btn-secondary px-4"><i class="fa-solid fa-file-pdf me-2"></i>Criar relatório em PDF</a>
                        </div>

                    </div>

                </div>
            </div>

            <div class="row mb-3">
                <div class="col text-center">
                    <a href="?ct=main&mt=index" class="btn btn-secondary px-4"><i class="fa-solid fa-chevron-left me-2"></i>Voltar</a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // datatable
        $('#table_agents').DataTable({
            pageLength: 10,
            pagingType: "full_numbers",
            language: {
                decimal: "",
                emptyTable: "Sem dados disponíveis na tabela.",
                info: "Mostrando _START_ até _END_ de _TOTAL_ registos",
                infoEmpty: "Mostrando 0 até 0 de 0 registos",
                infoFiltered: "(Filtrando _MAX_ total de registos)",
                infoPostFix: "",
                thousands: ",",
                lengthMenu: "Mostrando _MENU_ registos por página.",
                loadingRecords: "Carregando...",
                processing: "Processando...",
                search: "Filtrar:",
                zeroRecords: "Nenhum registro encontrado.",
                paginate: {
                    first: "Primeira",
                    last: "Última",
                    next: "Seguinte",
                    previous: "Anterior"
                },
                aria: {
                    sortAscending: ": ative para classificar a coluna em ordem crescente.",
                    sortDescending: ": ative para classificar a coluna em ordem decrescente."
                }
            }
        });
    })

    // chartjs
    <?php if (count($agents) != 0) : ?>

        new Chart(
            document.querySelector('#chartjs_chart'), {
                type: 'bar',
                data: {
                    labels: <?= $chart_labels ?>,
                    datasets: [{
                        label: 'Total de clientes por agente',
                        data: <?= $chart_totals ?>,
                        backgroundColor: 'rgb(50,100,200)',
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                },
            }
        );

    <?php endif; ?>
</script>