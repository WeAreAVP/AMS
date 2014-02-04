package gr.ntua.ivml.mint.util;

public class Tuple<U,V> {
	public U u;
	public V v;
	public Tuple( U u, V v ) {
		this.u = u;
		this.v = v;
	}
	public U first() { return u; }
	public V second() { return v; }
}
