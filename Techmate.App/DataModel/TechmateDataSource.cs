using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using Windows.Data.Json;

namespace Techmate.DataModel
{
    class TechmateDataSource
    {
        private static TechmateDataSource _dataSource = new TechmateDataSource();

        private ObservableCollection<Magazine> _magazines = new ObservableCollection<Magazine>();
        public ObservableCollection<Magazine> Magazines
        {
            get { return _magazines; }
        }

        public static async Task<IEnumerable<Magazine>> GetMagazinesAsync()
        {
            await _dataSource.GetMagazineListAsync();

            return _dataSource.Magazines;
        }

        public static async Task<Magazine> GetMagazineAsync(string uniqueId)
        {
            throw new NotImplementedException("GetMagazineAsync");
            //await _dataSource.GetSampleDataAsync();
            //// La semplice ricerca lineare è accettabile per piccoli set di dati
            //var matches = _dataSource.Groups.Where((group) => group.Number.Equals(uniqueId));
            //if (matches.Count() == 1) return matches.First();
            //return null;
        }

        public static async Task<Article> GetArticleAsync(string uniqueId)
        {
            throw new NotImplementedException("GetArticleAsync");
            //await _dataSource.GetSampleDataAsync();
            //// La semplice ricerca lineare è accettabile per piccoli set di dati
            //var matches = _dataSource.Groups.SelectMany(group => group.Articles).Where((item) => item.Number.Equals(uniqueId));
            //if (matches.Count() == 1) return matches.First();
            //return null;
        }

        private async Task GetMagazineListAsync()
        {
            if (_magazines.Count != 0)
                return;

            Uri dataUri = new Uri("http://127.0.0.1/Techmate/api/magazine");

            HttpClient httpClient = new HttpClient();
            string response =
                await httpClient.GetStringAsync(dataUri);

            JsonObject jsonObject = JsonObject.Parse(response);
            JsonArray jsonArray = jsonObject["Response"].GetArray();

            foreach (JsonValue groupValue in jsonArray)
            {
                JsonObject groupObject = groupValue.GetObject();
                Magazine group = new Magazine((int)groupObject["number"].GetNumber(),
                                              groupObject["cover"].GetString(),
                                              groupObject["color"].GetString(),
                                              groupObject["abstract"].GetString(),
                                              groupObject["publish_date"].GetString(),
                                              groupObject["content"].GetString());

                //foreach (JsonValue itemValue in groupObject["Items"].GetArray())
                //{
                //    JsonObject itemObject = itemValue.GetObject();
                //    group.Articles.Add(new Article(itemObject["UniqueId"].GetString(),
                //                                       itemObject["Title"].GetString(),
                //                                       itemObject["Subtitle"].GetString(),
                //                                       itemObject["ImagePath"].GetString(),
                //                                       itemObject["Description"].GetString(),
                //                                       itemObject["Content"].GetString()));
                //}
                Magazines.Add(group);
            }
        }
    }
}
