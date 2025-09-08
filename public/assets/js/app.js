function VM() {
  this.openAdd = () => alert('応募追加モーダル（仮）');
  this.sayHi   = () => alert('こんにちは！');
}

ko.applyBindings(new VM());