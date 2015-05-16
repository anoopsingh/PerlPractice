import xlsxwriter

workbook = xlsxwriter.Workbook('chart.xlsx')
worksheet = workbook.add_worksheet()

# Create a new Chart object.
chart = workbook.add_chart({'type': 'scatter', 'subtype':'smooth'})

# Write some data to add to plot on the chart.
data = [
    [1, 2, 3, 4, 5],
    [2, 4, 6, 8, 10],
    [3, 6, 9, 12, 15],
]

worksheet.write_column('A1', data[0])
worksheet.write_column('B1', data[1])
worksheet.write_column('C1', data[2])

# Configure the chart. In simplest case we add one or more data series.
chart.add_series(
                  {
                    'categories' : ['%s'%worksheet,0,0,4,1],
                    'values'     : ['%s'%worksheet,0,0,4,1],
                    'line'       : {'color': 'red'},
                    'name'       : '=%s!$C14' %worksheet,
                  })

chart.set_title({
    'name': 'Gis Anoop CPU Usage (Primary)',
    'name_font': {
        'name': 'Calibri',
        'color': 'blue',
      },
    })

chart.set_x_axis({
    'name': 'Time',
    'name_font': {
        'name': 'Courier New',
        'color': '#92D050'
    },
    'num_font': {
        'name': 'Arial',
        'color': '#00B0F0',
    },
    })

chart.set_y_axis({
    'name': 'CPU Utilization',
    'name_font': {
        'name': 'Century',
        'color': 'red'
    },
    'num_font': {
        'bold': True,
        'italic': True,
        'underline': True,
        'color': '#7030A0',
    },
   })
          
#chart.add_series({'values': '=Sheet1!$B$1:$B$5'})
#chart.add_series({'values': '=Sheet1!$C$1:$C$5'})

# Insert the chart into the worksheet.
worksheet.insert_chart('A7', chart)

workbook.close()
